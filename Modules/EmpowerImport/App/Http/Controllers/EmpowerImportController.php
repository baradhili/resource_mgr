<?php

namespace Modules\EmpowerImport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Models\Allocation;
use App\Models\ChangeRequest;
use App\Models\Demand;
use App\Models\Project;
use App\Models\PublicHoliday;
use App\Models\Region;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\Team;
use App\Models\Plugin;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use avadim\FastExcelReader\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmpowerImportController extends Controller
{
    protected $cacheService;

    // excel columns
    private $columnResourceName = 'A';

    private $columnEmpowerID = 'B';

    private $columnProjectName = 'C';

    private $columnProjectOwner = 'D';

    private $columnProjectStatus = 'E';

    private $columnProjectStart = 'F';

    private $columnProjectEnd = 'G';

    private $columnDataStart = 'H';
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * This function is used to populate the allocations table with data from an uploaded
     * Excel file. The Excel file should have the following columns:
     * - Resource name
     * - Empower ID
     * - Project name
     * - Project owner
     * - Project status
     * - Project start date
     * - Project end date
     * - Data start date
     * - Columns for each month of data (in the format 'YYYY-MM')
     *
     * The function will check if the resource type exists in the ResourceType table, and
     * if so, it will insert the data into the Demand table. If the resource type does not
     * exist, it will insert the data into the Allocations table.
     *
     * @return RedirectResponse
     */
    public function importEmpower(Request $request): RedirectResponse
    {
        Log::info("importEmpower");
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

            // Get the resource types - all of them because we do this as super admin
            $resourceTypes = ResourceType::all()->pluck('name')->map(function ($name) {
                return strtolower($name);
            })->toArray();
Log::info("got resource types");
            // collect resource types that have an associated team/resource manager - TODO don't get resources who's contract has ended
            $ownedResourceTypes = Team::select('resource_type')->distinct()->get()->pluck('resourceType')->unique();
Log::info("got owned resource types");
            // Initialize missingResources array
            $missingResources = [];
            // Generate the desired file name
            $currentDate = now()->format('Y-m-d');
            $fileName = "{$currentDate}_upload.xlsx";

            // Store the uploaded file with the generated name
            $path = $uploadedFile->storeAs('uploads', $fileName);
Log::info("importing {$fileName}");
            // Open XLSX-file
            $excel = Excel::open(Storage::path($path));
Log::info("opened file");
            $sheet = $excel->getSheet('Dataset_Empower');

            // Collect up the dates in row 5
            foreach ($sheet->nextRow() as $rowNum => $rowData) {
                Log::info("importing rows {$rowNum}");
                if ($rowNum == 5) { // Grab header row
                    // Step through columns 'G' on until blank, capture each filled column into array as monthYear
                    $monthYear = [];
                    foreach ($rowData as $columnLetter => $columnValue) {
                        if ($columnLetter >= $this->columnDataStart && !is_null($columnValue)) {
                            $monthYear[] = $columnValue;
                            $monthDate = Carbon::parse($columnValue)->startOfMonth()->format('Y-m-d');
                        }
                    }
                }
                if ($rowNum < 6) { // Skip first 5 rows
                    continue;
                } elseif ($rowData[$this->columnEmpowerID] != null) { // Ignore empty lines
                    $resourceName = $rowData[$this->columnResourceName] ?? $resourceName;

                    $resourceNameLower = strtolower($resourceName);
                    $contains = in_array($resourceNameLower, $resourceTypes);

                    if (!$contains) {

                        $resource = Resource::where('empowerID', $resourceName)->first();
                        $resourceID = $resource->id ?? null;
                        if (is_null($resourceID)) {
                            $missingResources[] = $resourceName;
                        } else {
                            $projectID = $this->checkProject($rowData);
                            // check the month allocations
                            for ($i = 0; $i < count($monthYear); $i++) {
                                $columnLetter = chr(ord($this->columnDataStart) + $i);
                                $fte = (double) number_format(min(max((float) $rowData[$columnLetter], 0.00), 9.99), 2, '.', '');
                                $existingAllocation = Allocation::where('resources_id', $resourceID)
                                    ->where('projects_id', $projectID)
                                    ->where('allocation_date', Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'))
                                    ->first();
                                // if its a change
                                if ($existingAllocation && $existingAllocation->fte != $fte) {
                                    // Log::info("Warning: FTE for resource {$resourceName} on project {$projectID} on date {$monthYear[$i]} has changed from {$existingAllocation->fte} to $fte");
                                    // 'user' = Importer
                                    ChangeRequest::create([
                                        'record_type' => Allocation::class,
                                        'record_id' => $existingAllocation->id,
                                        'field' => 'fte',
                                        'old_value' => $existingAllocation->fte,
                                        'new_value' => $fte,
                                        'status' => 'pending',
                                        // 'requested_by' => 0, // 0 will indicate teh import function - otherwise we put the user id
                                    ]);
                                } elseif (!$existingAllocation) {
                                    Allocation::create([
                                        'resources_id' => $resourceID,
                                        'projects_id' => $projectID,
                                        'allocation_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'),
                                        'fte' => $fte,
                                        'status' => 'Proposed',
                                        'source' => 'Imported',
                                    ]);
                                }

                            }
                        }
                    } else { // Insert these into demand

                        $projectID = $this->checkProject($rowData);
                        // first replace the "resource name" with a resource_type id
                        $resourceType = ResourceType::where('name', 'LIKE', $resourceName . '%')->first();

                        // Check if the resource type belongs to a team aka someone is going to manage this demand 
                        $belongsToTeam = $ownedResourceTypes->contains(function ($resourceType) use ($resourceName) {
                            return strtolower($resourceType->name) === strtolower($resourceName);
                        });
                        if ($belongsToTeam) { //if it belongs to a team, insert it into demand, otherwise skip

                            $rowData[$this->columnResourceName] = $resourceType ? $resourceType->id : null;
                            Log::info("matched demand resource type {$resourceName} to {$resourceType->id}");
                            // we should ignore past demand 
                            for ($i = 0; $i < count($monthYear); $i++) {
                                $columnLetter = chr(ord($this->columnDataStart) + $i); // 'H' + i
                                $fte = (double) number_format(min(max((float) $rowData[$columnLetter], 0.00), 9.99), 2, '.', '');
                                $existingDemand = Demand::where('projects_id', $projectID)
                                    ->where('demand_date', Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'))
                                    ->where('resource_type', $resourceType->id)
                                    ->first();
                                // if its a change
                                if ($existingDemand && $existingDemand->fte != $fte) {
                                    Log::info("Warning: FTE for demand {$projectID} on date {$monthYear[$i]} has changed from {$existingDemand->fte} to $fte");
                                    ChangeRequest::create([
                                        'record_type' => Demand::class,
                                        'record_id' => $existingDemand->id,
                                        'field' => 'fte',
                                        'old_value' => $existingDemand->fte,
                                        'new_value' => $fte,
                                        'status' => 'pending',
                                        // 'requested_by' => 0, // 0 will indicate teh import function - otherwise we put the user id
                                    ]);
                                } elseif (!$existingDemand) {

                                    $resourceType = ResourceType::where('name', 'like', "$resourceName%")->first();
                                    $resourceTypeId = $resourceType ? $resourceType->id : $resourceName;

                                    Demand::create([
                                        'projects_id' => $projectID,
                                        'demand_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'),
                                        'fte' => $fte,
                                        'resource_type' => $resourceTypeId,
                                        'status' => 'Proposed',
                                        'source' => 'Imported',
                                    ]);
                                }
                            }
                        } else {
                            // Log::info("Skipping demand resource type {$resourceName} as it does not belong to a team");
                        }
                    }
                }
            }
        }

        // update the cache
        $this->cacheService->cacheResourceAllocation();

        if (!empty($missingResources)) {
            $missingResourceList = implode(', ', $missingResources);

            return redirect()->back()->with('error', "The following resources were not found: $missingResourceList");
        } else {
            return redirect()->back()->with('success', 'Data staged successfully for further processing.');
        }
    }

    /**
     * Given a row of data from the import file, this function checks if the project exists,
     * and if it does, checks if the start and end dates have changed. It then creates or
     * updates the project in the database.
     *
     * @param  array  $rowData  the row of data from the import file
     * @return int the id of the project in the database
     */
    private function checkProject($rowData)
    {
        $empowerID = $rowData[$this->columnEmpowerID];
        $projectName = preg_replace('/[^\x00-\x7F]/', '', $rowData[$this->columnProjectName]);
        $project = Project::where('empowerID', $empowerID)->first();
        $projectID = $project->id ?? null;
        $projectStatus = $rowData[$this->columnProjectStatus];
        $projectOwner = $rowData[$this->columnProjectOwner];
        // clean up projectOwner - truncate any org suffix that may be in there
        $projectOwner = preg_replace('/\s*\([^)]*\)\s*$/', '', $projectOwner);
        $projectOwner = trim($projectOwner);
        // data is in excel like 15/05/24 needs to be in 2024-05-15
        $projectStart = Carbon::createFromFormat('d/m/y', $rowData[$this->columnProjectStart])->format('Y-m-d');
        $projectEnd = Carbon::createFromFormat('d/m/y', $rowData[$this->columnProjectEnd])->format('Y-m-d');
        // check if project exists and check start and end for changes
        if (!is_null($projectID)) {
            $projectInDB = Project::find($projectID);
            if ($projectInDB->start_date != $projectStart || $projectInDB->end_date != $projectEnd) {
                // do stuff later if we need to
            }
        }
        // for the moment we won't handle changes
        $project = Project::updateOrCreate(
            ['empowerID' => $empowerID],
            [
                'name' => $projectName,
                'start_date' => $projectStart,
                'end_date' => $projectEnd,
                'projectManager' => $projectOwner,
            ]
        );
        $projectID = $project->id;

        return $projectID;
    }
}
