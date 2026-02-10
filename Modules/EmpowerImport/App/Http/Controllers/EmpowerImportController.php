<?php

namespace Modules\EmpowerImport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\ChangeRequest;
use App\Models\Client;
use App\Models\Demand;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\Team;
use App\Services\CacheService;
use avadim\FastExcelReader\Excel;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

    private $columnProjectClient = 'H';

    private $columnDataStart = 'I';

    private $columnDemandID = 'A';

    private $columnDemandType = 'B';

    private $columnDemandTitle = 'C';

    private $columnDemandOwner = 'D';

    private $columnDemandStatus = 'E';

    private $columnDemandDuration = 'F';

    private $columnDemandCapacity = 'G';

    private $columnDemandFunded = 'H';

    private $columnDemandDetails = 'I';

    private $columnDemandLastChange = 'J';

    private $columnDemandExpectedStart = 'K';

    private $columnDemandDemandMonth = 'L';

    private $columnDemandUnallocated = 'M';

    private $columnDemandInScope = 'N';

    private $columnDemandFTE = 'O';

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
     */
    public function importEmpower(Request $request): RedirectResponse
    {
        // Log::info("importEmpower");
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

            // Get the resource types - all of them because we do this as super admin
            $resourceTypes = ResourceType::all()->pluck('name')->map(function ($name) {
                return strtolower($name);
            })->toArray();

            // collect resource types that have an associated team/resource manager - TODO don't get resources who's contract has ended
            $ownedResourceTypes = Team::select('resource_type')->distinct()->get()->pluck('resourceType')->unique();

            // Initialize missingResources array
            $missingResources = [];
            // Generate the desired file name
            $currentDate = now()->format('Y-m-d');
            $fileName = "{$currentDate}_upload.xlsx";

            // Store the uploaded file with the generated name
            $path = $uploadedFile->storeAs('uploads', $fileName);

            // Open XLSX-file
            $excel = Excel::open(Storage::path($path));

            // pass $excel to handleAllocation
            $this->handleAllocation($excel, $ownedResourceTypes, $resourceTypes, $missingResources);
            $this->handleDemand($excel);

        }

        // update the cache
        $this->cacheService->cacheResourceAllocation();

        if (! empty($missingResources)) {
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

        // Clean project name
        $projectName = preg_replace('/[^\x00-\x7F]/', '', $rowData[$this->columnProjectName]);

        // --- Handle Client Relationship ---
        $clientName = trim($rowData[$this->columnProjectClient]);
        // FirstOrCreate checks if a client with this name exists. If not, it creates one.
        $client = Client::firstOrCreate(['name' => $clientName]);
        // ----------------------------------

        $projectOwner = $rowData[$this->columnProjectOwner];
        // clean up projectOwner - truncate any org suffix that may be in there
        $projectOwner = preg_replace('/\s*\([^)]*\)\s*$/', '', $projectOwner);
        $projectOwner = trim($projectOwner);

        // data is in excel like 15/05/24 needs to be in 2024-05-15
        // Added check to ensure data exists before parsing to avoid Carbon errors
        $projectStart = ! empty($rowData[$this->columnProjectStart])
            ? Carbon::createFromFormat('d/m/y', $rowData[$this->columnProjectStart])->format('Y-m-d')
            : null;

        $projectEnd = ! empty($rowData[$this->columnProjectEnd])
            ? Carbon::createFromFormat('d/m/y', $rowData[$this->columnProjectEnd])->format('Y-m-d')
            : null;

        $projectStatus = $this->cleanProjectStatus($rowData[$this->columnProjectStatus]);

        // IMPROVEMENT: We do not need to manually find the project before updateOrCreate.
        // updateOrCreate handles the "check if exists" logic internally.
        // The redundant query has been removed to improve performance.

        // Prepare the attributes to update
        $attributes = [
            'name' => $projectName,
            'start_date' => $projectStart,
            'end_date' => $projectEnd,
            'projectManager' => $projectOwner,
            'client_id' => $client->id, // Link the client
            'status' => $projectStatus ?? null, // Added status saving (was extracted but not saved in original)
        ];

        $project = Project::updateOrCreate(
            ['empowerID' => $empowerID], // Criteria to find the project
            $attributes                   // Data to update or create
        );

        return $project->id;
    }

    private function handleDemand(Excel $excel)
    {

        return true;
    }

    private function handleAllocation(Excel $excel, Collection $ownedResourceTypes, array $resourceTypes, array &$missingResources)
    {
        $sheet = $excel->getSheet('Dataset_Empower');

        // Collect up the dates in row 5
        foreach ($sheet->nextRow() as $rowNum => $rowData) {
            // Log::info("importing rows {$rowNum}");
            if ($rowNum == 5) { // Grab header row
                // Step through columns 'G' on until blank, capture each filled column into array as monthYear
                $monthYear = [];
                foreach ($rowData as $columnLetter => $columnValue) {
                    if ($columnLetter >= $this->columnDataStart && ! is_null($columnValue)) {
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

                if (! $contains) {

                    $resource = Resource::where('empowerID', $resourceName)->first();
                    $resourceID = $resource->id ?? null;
                    if (is_null($resourceID)) {
                        $missingResources[] = $resourceName;
                    } else {
                        $projectID = $this->checkProject($rowData);
                        // check the month allocations
                        for ($i = 0; $i < count($monthYear); $i++) {
                            $columnLetter = chr(ord($this->columnDataStart) + $i);
                            $fte = (float) number_format(min(max((float) $rowData[$columnLetter], 0.00), 9.99), 2, '.', '');
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
                            } elseif (! $existingAllocation) {
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
                } // else { // Insert these into demand

                //     $projectID = $this->checkProject($rowData);
                //     // first replace the "resource name" with a resource_type id
                //     $resourceType = ResourceType::where('name', 'LIKE', $resourceName . '%')->first();

                //     // Check if the resource type belongs to a team aka someone is going to manage this demand
                //     $belongsToTeam = $ownedResourceTypes->contains(function ($resourceType) use ($resourceName) {
                //         return strtolower($resourceType->name) === strtolower($resourceName);
                //     });
                //     if ($belongsToTeam) { //if it belongs to a team, insert it into demand, otherwise skip

                //         $rowData[$this->columnResourceName] = $resourceType ? $resourceType->id : null;
                //         Log::info("matched demand resource type {$resourceName} to {$resourceType->id}");
                //         // we should ignore past demand
                //         for ($i = 0; $i < count($monthYear); $i++) {
                //             $columnLetter = chr(ord($this->columnDataStart) + $i); // 'H' + i
                //             $fte = (double) number_format(min(max((float) $rowData[$columnLetter], 0.00), 9.99), 2, '.', '');
                //             $existingDemand = Demand::where('projects_id', $projectID)
                //                 ->where('demand_date', Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'))
                //                 ->where('resource_type', $resourceType->id)
                //                 ->first();
                //             // if its a change
                //             if ($existingDemand && $existingDemand->fte != $fte) {
                //                 Log::info("Warning: FTE for demand {$projectID} on date {$monthYear[$i]} has changed from {$existingDemand->fte} to $fte");
                //                 ChangeRequest::create([
                //                     'record_type' => Demand::class,
                //                     'record_id' => $existingDemand->id,
                //                     'field' => 'fte',
                //                     'old_value' => $existingDemand->fte,
                //                     'new_value' => $fte,
                //                     'status' => 'pending',
                //                     // 'requested_by' => 0, // 0 will indicate teh import function - otherwise we put the user id
                //                 ]);
                //             } elseif (!$existingDemand) {

                //                 $resourceType = ResourceType::where('name', 'like', "$resourceName%")->first();
                //                 $resourceTypeId = $resourceType ? $resourceType->id : $resourceName;

                //                 Demand::create([
                //                     'projects_id' => $projectID,
                //                     'demand_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'),
                //                     'fte' => $fte,
                //                     'resource_type' => $resourceTypeId,
                //                     'status' => 'Proposed',
                //                     'source' => 'Imported',
                //                 ]);
                //             }
                //         }
                //     } else {
                //         // Log::info("Skipping demand resource type {$resourceName} as it does not belong to a team");
                //     }
                // }
            }
        }

        return true;
    }

    private function convertExcelDate($dateValue)
    {
        $unixDate = ($dateValue); // * 86400;

        return gmdate('Y-m-d', $unixDate);
        //   'where Y is YYYY, m is MM, and d is DD
    }

    private function cleanDurationData($durationData)
    {
        preg_match('/(\d+(?:\.\d+)?)\s*(years?|months?|weeks?|days?)?/i', $durationData, $matches);
        $value = (float) $matches[1];
        $unit = isset($matches[2]) ? strtolower($matches[2]) : 'months';
        switch ($unit) {
            case 'years':
                $value *= 12;
                break;
            case 'months':
                break;
            case 'weeks':
                $value *= (7 / 30);
                break;
            case 'days':
                $value /= 30;
                break;
        }

        return $value;
    }

    private function cleanProjectName($projectName)
    {
        // find the project or create a new one
        // extract EmpowerID or project name from demand details
        preg_match('/([A-Z])(\d{4})|([A-Za-z\s\-]+(?:\s-\s)?([A-Za-z\s]+(?:\s\d+)?)?)/', $projectName, $matches);
        $demandEmpowerID = $matches[1] ?? null;
        $demandProjectName = $matches[3] ?? null;
        // Look up the project in the Project Model
        $project = null;
        if (! is_null($demandEmpowerID)) {
            $project = Project::where('empowerID', $demandEmpowerID)->first();
        } elseif (! is_null($demandProjectName)) {
            $project = Project::where('name', 'LIKE', "%{$demandProjectName}%")->first();
        }
        // if we have a match thenus it, otherwise flag
        if (! is_null($project)) {
            $projectID = $project->id;
            Log::info("found a project for {{$projectName}} - {{$project->empoerID}}");
        } else {
            $projectID = null;
            Log::info("could not find a project for {{$projectName}}");
        }

        return $projectID;
    }

    /**
     * Maps raw status strings from the import file to valid database ENUM values.
     *
     * @param  string  $rawStatus  The status string from the import file.
     * @return string|null The cleaned status matching the DB ENUM, or null if no match.
     */
    private function cleanProjectStatus($rawStatus)
    {
        // Trim whitespace to ensure accurate matching
        $status = trim($rawStatus);

        // Mapping of Import Values => Database ENUM Values
        $mapping = [
            // Early stage maps to Proposed
            'Idea' => 'Proposed',
            'Concept Ready' => 'Proposed',
            'Readiness' => 'Proposed',

            // Active delivery/planning stages map to Active
            'Initiate' => 'Active',
            'Plan' => 'Active',
            'Project Start' => 'Active',
            'Execute/Verify' => 'Active',
            'Program Delivery' => 'Active',
            'Commission/Handover' => 'Active',

            // Closing stages map to Completed
            'Project Complete' => 'Completed',
            'Program Completed' => 'Completed',
            'Benefit/Close' => 'Completed',
            'Program Close' => 'Completed',
        ];

        // Return the mapped value, or null if the status isn't recognized
        // (Since the DB column is nullable, returning null is safe)
        return $mapping[$status] ?? null;
    }
}
