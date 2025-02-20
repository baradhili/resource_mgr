<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Project;
use App\Models\Demand;
use App\Models\Allocation;
use App\Models\StagingAllocation;
use App\Models\StagingDemand;
use App\Models\ResourceType;
use App\Models\Region;
use App\Models\PublicHoliday;
use \avadim\FastExcelReader\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;


class ImportController extends Controller
{

    public function index()
    {
        return view('import.index');
    }
    public function populateAllocations(Request $request)
    {
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

            // Get the resource types - all of them because we do this as super admin
            $resourceTypes = ResourceType::all()->pluck('name')->map(function ($name) {
                return strtolower($name);
            })->toArray();

            // Initialize missingResources array
            $missingResources = [];
            // Generate the desired file name
            $currentDate = now()->format('Y-m-d');
            $fileName = "{$currentDate}_upload.xlsx";

            // Store the uploaded file with the generated name
            $path = $uploadedFile->storeAs('uploads', $fileName);

            // Open XLSX-file
            $excel = Excel::open(Storage::path($path));

            $result = [
                'sheets' => $excel->getSheetNames() // get all sheet names
            ];

            $sheet = $excel->getSheet('Dataset_Empower');

            // Collect up the dates in row 5
            foreach ($sheet->nextRow() as $rowNum => $rowData) {
                if ($rowNum == 5) { // Grab header row
                    // Step through columns 'G' on until blank, capture each filled column into array as monthYear
                    $monthYear = [];
                    foreach ($rowData as $columnLetter => $columnValue) {
                        if ($columnLetter >= 'G' && !is_null($columnValue)) {
                            $monthYear[] = $columnValue;
                            $monthDate = Carbon::parse($columnValue)->startOfMonth()->format('Y-m-d');
                        }
                    }
                    // Log::info("months " . print_r($monthYear, true));
                }
                if ($rowNum < 6) { // Skip first 5 rows
                    continue;
                } elseif ($rowData['B'] != null) { // Ignore empty lines
                    $resourceName = $rowData['A'] ?? $resourceName;

                    $resourceNameLower = strtolower($resourceName);
                    // Check if any of the resource types are contained within the resource name
                    $contains = false;
                    foreach ($resourceTypes as $type) {
                        if (Str::contains($resourceNameLower, $type)) {
                            $contains = true;
                            Log::info("its a demand");
                            break;
                        }
                    }

                    if (!$contains) {
                        $resource = Resource::where('empowerID', $resourceName)->first();
                        $resourceID = $resource->id ?? null;
                        if (is_null($resourceID)) {
                            $missingResources[] = $resourceName;
                        } else {
                            $projectID = $this->checkProject($rowData);

                            for ($i = 0; $i < count($monthYear); $i++) {
                                $columnLetter = chr(71 + $i); // 'G' + i
                                $fte = (double) number_format((float) $rowData[$columnLetter], 2, '.', '');
                                $existingAllocation = Allocation::where('resources_id', $resourceID)
                                    ->where('projects_id', $projectID)
                                    ->where('allocation_date', Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'))
                                    ->first();
                                if ($existingAllocation && $existingAllocation->fte != $fte) {
                                    Log::info("Warning: FTE for resource {$resourceName} on project {$projectID} on date {$monthYear[$i]} has changed from {$existingAllocation->fte} to $fte");
                                }

                                if ($fte > 0) {
                                    Allocation::updateOrCreate(
                                        [
                                            'resources_id' => $resourceID,
                                            'projects_id' => $projectID,
                                            'allocation_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d')
                                        ],
                                        [
                                            'fte' => $fte,
                                            'status' => 'Proposed',
                                            'source' => 'Imported'
                                        ]
                                    );
                                }
                            }
                        }
                    } else { // Insert these into demand
                        $projectID = $this->checkProject($rowData);

                        for ($i = 0; $i < count($monthYear); $i++) {
                            $columnLetter = chr(71 + $i); // 'G' + i
                            $fte = (double) number_format((float) $rowData[$columnLetter], 2, '.', '');
                            $existingDemand = Demand::where('projects_id', $projectID)
                                ->where('demand_date', Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d'))
                                ->first();
                            if ($existingDemand && $existingDemand->fte != $fte) {
                                Log::info("Warning: FTE for demand {$projectID} on date {$monthYear[$i]} has changed from {$existingDemand->fte} to $fte");
                            }
                            if ($fte > 0) {
                                Demand::updateOrCreate(
                                    [
                                        'projects_id' => $projectID,
                                        'demand_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d')
                                    ],
                                    [
                                        'fte' => $fte,
                                        'status' => 'Proposed',
                                        'resource_type' => $resourceName,
                                        'source' => 'Imported'
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }

        if (!empty($missingResources)) {
            $missingResourceList = implode(', ', $missingResources);
            return redirect()->back()->with('error', "The following resources were not found: $missingResourceList");
        } else {
            return redirect()->back()->with('success', 'Data staged successfully for further processing.');
        }
    }

    private function checkProject($rowData)
    {
        $empowerID = $rowData['B'];
        $projectName = preg_replace('/[^\x00-\x7F]/', '', $rowData['C']);
        $project = Project::where('empowerID', $empowerID)->first();
        $projectID = $project->id ?? null;
        $projectStatus = $rowData['D'];
        //data is in excel like 15/05/24 needs to be in 2024-05-15
        $projectStart = Carbon::createFromFormat('d/m/y', $rowData['E'])->format('Y-m-d');
        $projectEnd = Carbon::createFromFormat('d/m/y', $rowData['F'])->format('Y-m-d');
        // Log::info("project data: " . $projectID . " " . $projectName . " " . $projectStart . " " . $projectEnd);
        //check if project exists and check start and end for changes
        if (!is_null($projectID)) {
            $projectInDB = Project::find($projectID);
            if ($projectInDB->start_date != $projectStart || $projectInDB->end_date != $projectEnd) {
                Log::info("Project start or end date does not match for projectID: $projectID");
                Log::info("starts: " . $projectInDB->start_date . " " . $projectStart);
                Log::info("ends: " . $projectInDB->end_date . " " . $projectEnd);
            }
        }
        //for the moment we won't handle changes
        $project = Project::updateOrCreate(
            ['empowerID' => $empowerID],
            [
                'name' => $projectName,
                'start_date' => $projectStart,
                'end_date' => $projectEnd
            ]
        );
        $projectID = $project->id;

        return $projectID;
    }


    public function reviewDemands()
    {
        $stagedDemands = StagingDemand::where('status', '<>', 'Rejected')
            ->with('project')
            ->get();
        $demands = Demand::all();
        $changes = [];
        // TODO figure out how to check for changes where a demand or an allocation is deleted
        foreach ($stagedDemands as $stagedDemand) {

            // Check if we have an existing Demand
            $demand = $demands->firstWhere('projects_id', $stagedDemand->projects_id);
            if ($demand) {
                $demand = $demand->where('demand_date', $stagedDemand->demand_date)->first();
            }

            //If we have an existing demand then process as a change
            if ($demand) {
                if ($stagedDemand->fte != $demand->fte) {
                    $lastChange = end($changes);

                    if (
                        $lastChange && $lastChange['project'] === $stagedDemand->project->name &&
                        $lastChange['resource'] === $stagedDemand->resource_type &&
                        $lastChange['new_ftes'] === $stagedDemand->fte &&
                        Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedDemand->demand_date))
                    ) {

                        $changes[key($changes)]['end'] = $stagedDemand->demand_date;
                    } else {
                        $changes[] = [
                            'id' => $stagedDemand->id,
                            'project' => $stagedDemand->project->name,
                            'project_id' => $stagedDemand->projects_id,
                            'start' => $stagedDemand->demand_date,
                            'end' => $stagedDemand->demand_date,
                            'resource' => $stagedDemand->resource_type,
                            'old_ftes' => $demand->fte,
                            'new_ftes' => $stagedDemand->fte,
                        ];
                    }
                }
            } else {
                //Check if this demand has already been allocated
                $allocation = Allocation::where('projects_id', $stagedDemand->projects_id)
                    ->where('allocation_date', $stagedDemand->demand_date)
                    ->first();

                if ($allocation) {
                    continue;
                }
                //otherise compact if there are sequential identical FTE allocations
                $lastChange = end($changes);

                if (
                    $lastChange && $lastChange['project'] === $stagedDemand->project->name &&
                    $lastChange['resource'] === $stagedDemand->resource_type &&
                    $lastChange['new_ftes'] === $stagedDemand->fte &&
                    Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedDemand->demand_date))
                ) {

                    $changes[key($changes)]['end'] = $stagedDemand->demand_date;
                } else {
                    $changes[] = [
                        'id' => $stagedDemand->id,
                        'project' => $stagedDemand->project->name,
                        'project_id' => $stagedDemand->projects_id,
                        'start' => $stagedDemand->demand_date,
                        'end' => $stagedDemand->demand_date,
                        'resource' => $stagedDemand->resource_type,
                        'old_ftes' => 0,
                        'new_ftes' => $stagedDemand->fte,
                    ];
                }
            }
        }

        return view('import.reviewDemands', compact('changes'));
    }


    public function reviewAllocations()
    {
        $stagedAllocations = StagingAllocation::where('status', '<>', 'Rejected')->get();
        $allocations = Allocation::all();
        $changes = [];

        foreach ($stagedAllocations as $stagedAllocation) {
            $allocation = $allocations->firstWhere('projects_id', $stagedAllocation->projects_id);
            if ($allocation) {
                $allocation = $allocation->where('allocation_date', $stagedAllocation->allocation_date)->first();
            }

            if ($allocation) {
                if ($stagedAllocation->fte != $allocation->fte) {
                    $lastChange = end($changes);

                    if (
                        $lastChange && $lastChange['project'] === $stagedAllocation->project->name &&
                        $lastChange['resource'] === $stagedAllocation->resource_type &&
                        $lastChange['new_ftes'] === $stagedAllocation->fte &&
                        Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedAllocation->allocation_date))
                    ) {

                        $changes[key($changes)]['end'] = $stagedAllocation->allocation_date;
                    } else {
                        $changes[] = [
                            'id' => $stagedAllocation->id,
                            'project' => $stagedAllocation->project->name,
                            'project_id' => $stagedAllocation->projects_id,
                            'start' => $stagedAllocation->allocation_date,
                            'end' => $stagedAllocation->allocation_date,
                            'resource' => $stagedAllocation->resource->full_name,
                            'old_ftes' => $allocation->fte,
                            'new_ftes' => $stagedAllocation->fte,
                        ];
                    }
                }
            } else {
                $lastChange = end($changes);

                if (
                    $lastChange && $lastChange['project'] === $stagedAllocation->project->name &&
                    $lastChange['resource'] === $stagedAllocation->resource_type &&
                    $lastChange['new_ftes'] === $stagedAllocation->fte &&
                    Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedAllocation->allocation_date))
                ) {

                    $changes[key($changes)]['end'] = $stagedAllocation->allocation_date;
                } else {
                    $changes[] = [
                        'id' => $stagedAllocation->id,
                        'project' => $stagedAllocation->project->name,
                        'project_id' => $stagedAllocation->projects_id,
                        'start' => $stagedAllocation->allocation_date,
                        'end' => $stagedAllocation->allocation_date,
                        'resource' => $stagedAllocation->resource_type,
                        'old_ftes' => 0,
                        'new_ftes' => $stagedAllocation->fte,
                    ];
                }
            }
        }

        return view('import.reviewAllocations', compact('changes'));
    }
    public function handleReviewAction(Request $request)
    {
        $referringURL = $request->headers->get('referer');
        $validatedData = $request->validate([
            'change' => 'required|array',
            'action' => 'required|string|in:Accept,Reject',
            'type' => 'required|string|in:Demand,Allocation',
        ]);

        $change = $validatedData['change'];
        $action = $validatedData['action'];
        $type = $validatedData['type'];

        if ($type === 'Demand') {
            if ($action === 'Accept') {
                // Handle acceptance logic for Demand
                // Example: Update Demand model with new data
                Log::info("accepted demand: " . print_r($change, true));
                if ($change['end'] = $change['start']) {
                    $project = Project::where('name', $change['project'])->first();
                    $change['project_id'] = $project->id;
                    $demand = Demand::firstOrCreate([
                        'projects_id' => $change['project_id'],
                        'demand_date' => $change['start'],
                    ], [
                        'fte' => $change['new_ftes'],
                        'status' => 'Proposed',
                        'resource_type' => $change['resource'],
                    ]);
                    StagingDemand::where('id', $change['id'])->delete();
                    Artisan::call('app:refresh-cache');
                } else {
                    // we have a range of months
                    $project = Project::where('name', $change['project'])->first();
                    $change['project_id'] = $project->id;

                    $currentDate = Carbon::parse($change['start']);
                    $endDate = Carbon::parse($change['end']);

                    while ($currentDate->lte($endDate)) {
                        Demand::firstOrCreate([
                            'projects_id' => $change['project_id'],
                            'demand_date' => $currentDate->format('Y-m-d'),
                        ], [
                            'fte' => $change['new_ftes'],
                            'status' => 'Proposed',
                            'resource_type' => $change['resource'],
                        ]);
                        $currentDate->addMonth();
                    }

                    StagingDemand::where('id', $change['id'])->delete();
                    Artisan::call('app:refresh-cache');

                }

            } elseif ($action === 'Reject') {
                // Handle rejection logic for Demand
                // Example: Remove or ignore changes
                Log::info("rejected demand: " . print_r($change, true));
                StagingDemand::where('id', $change['id'])->update(['status' => 'Rejected']);
            }
        } elseif ($type === 'Allocation') {
            if ($action === 'Accept') {
                $allocation = Allocation::firstOrCreate([
                    'projects_id' => $change['project_id'],
                    'allocation_date' => $change['start'],
                ], [
                    'fte' => $change['new_ftes'],
                    'status' => 'Proposed',
                    'resource_type' => $change['resource'],
                ]);
                StagingAllocation::where('id', $change['id'])->delete();
                Artisan::call('app:refresh-cache');
            } elseif ($action === 'Reject') {
                Log::info("rejected allocation: " . print_r($change, true));
                StagingAllocation::where('id', $change['id'])->update(['status' => 'Rejected']);
                Artisan::call('app:refresh-cache');
            }
        }

        // Return response, e.g., redirect or JSON response
        return redirect($referringURL)->with('success', 'Action processed successfully.');
    }


    public function importHolidays()
    {
        $url = 'https://data.gov.au/data/dataset/b1bc6077-dadd-4f61-9f8c-002ab2cdff10/resource/33673aca-0857-42e5-b8f0-9981b4755686/download/australian-public-holidays-combined-2021-2025.csv';
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);
        $stream = $response->getBody();
        $rows = array_map('str_getcsv', explode("\n", $stream));
        $header = array_shift($rows);
        $data = [];
        foreach ($rows as $row) {
            $data[] = array_combine($header, $row);
        }
        foreach (array_slice($data, 1) as $holiday) { // Skip the first row
            $region = Region::where('jurisdiction', strtolower($holiday['Jurisdiction']))->first();
            if ($region) {
                $holidayData = [
                    'region_id' => $region->id,
                    'date' => $holiday['Date'],
                    'name' => $holiday['Holiday Name'],
                ];
                PublicHoliday::updateOrCreate([
                    'region_id' => $region->id,
                    'date' => $holiday['Date']
                ], $holidayData);
            }
        }
        return redirect()->back()->with('success', 'Public Holidays imported successfully.');
    }

}
