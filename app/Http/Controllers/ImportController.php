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
use \avadim\FastExcelReader\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
                    // Step through columns 'D' on until blank, capture each filled column into array as monthYear
                    $monthYear = [];
                    foreach ($rowData as $columnLetter => $columnValue) {
                        if ($columnLetter >= 'D' && !is_null($columnValue)) {
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
                            break;
                        }
                    }

                    if (!$contains) {
                        $resource = Resource::where('empowerID', $resourceName)->first();
                        $resourceID = $resource->id ?? null;
                        if (is_null($resourceID)) {
                            $missingResources[] = $resourceName;
                        } else {
                            $empowerID = $rowData['B'];
                            $projectName = preg_replace('/[^\x00-\x7F]/', '', $rowData['C']);
                            $project = Project::where('empowerID', $empowerID)->first();
                            $projectID = $project->id ?? null;
                            if (is_null($projectID)) {
                                $project = Project::updateOrCreate(
                                    ['empowerID' => $empowerID],
                                    ['name' => $projectName]
                                );
                                $projectID = $project->id;
                            }

                            for ($i = 0; $i < count($monthYear); $i++) {
                                $columnLetter = chr(68 + $i); // 'D' + i
                                $fte = (double) $rowData[$columnLetter];
                                if ($fte > 0) {
                                    StagingAllocation::updateOrCreate(
                                        [
                                            'resources_id' => $resourceID,
                                            'projects_id' => $projectID,
                                            'allocation_date' => Carbon::createFromFormat('Y-m', $monthYear[$i])->startOfMonth()->format('Y-m-d')
                                        ],
                                        [
                                            'fte' => $fte,
                                            'status' => 'Staged',
                                            'source' => 'Imported'
                                        ]
                                    );
                                }
                            }
                        }
                    } else { // Insert these into demand
                        $empowerID = $rowData['B'];
                        $projectName = preg_replace('/[^\x00-\x7F]/', '', $rowData['C']);
                        $project = Project::where('empowerID', $empowerID)->first();
                        $projectID = $project->id ?? null;
                        if (is_null($projectID)) {
                            $project = Project::updateOrCreate(
                                ['empowerID' => $empowerID],
                                ['name' => $projectName]
                            );
                            $projectID = $project->id;
                        }

                        for ($i = 0; $i < count($monthYear); $i++) {
                            $columnLetter = chr(68 + $i); // 'D' + i
                            $fte = (double) $rowData[$columnLetter];
                            if ($fte > 0) {
                                StagingDemand::updateOrCreate(
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

    
    public function reviewDemands()
    {
        $stagedDemands = StagingDemand::where('status', '<>', 'Rejected')->get();
        $demands = Demand::all();
        $changes = [];
        
        foreach ($stagedDemands as $stagedDemand) {
            $demand = $demands->firstWhere('projects_id', $stagedDemand->projects_id);
            if ($demand) {
                $demand = $demand->where('demand_date', $stagedDemand->demand_date)->first();
            }
            
            if ($demand) {
                if ($stagedDemand->fte != $demand->fte) {
                    $lastChange = end($changes);

                    if ($lastChange && $lastChange['project'] === $stagedDemand->project->name &&
                        $lastChange['resource'] === $stagedDemand->resource_type &&
                        $lastChange['new_ftes'] === $stagedDemand->fte &&
                        Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedDemand->demand_date))) {
                        
                        $changes[key($changes)]['end'] = $stagedDemand->demand_date;
                    } else {
                        $changes[] = [
                            'project' => $stagedDemand->project->name,
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

                if ($lastChange && $lastChange['project'] === $stagedDemand->project->name &&
                    $lastChange['resource'] === $stagedDemand->resource_type &&
                    $lastChange['new_ftes'] === $stagedDemand->fte &&
                    Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedDemand->demand_date))) {
                    
                    $changes[key($changes)]['end'] = $stagedDemand->demand_date;
                } else {
                    $changes[] = [
                        'project' => $stagedDemand->project->name,
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

                    if ($lastChange && $lastChange['project'] === $stagedAllocation->project->name &&
                        $lastChange['resource'] === $stagedAllocation->resource_type &&
                        $lastChange['new_ftes'] === $stagedAllocation->fte &&
                        Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedAllocation->allocation_date))) {
                        
                        $changes[key($changes)]['end'] = $stagedAllocation->allocation_date;
                    } else {
                        $changes[] = [
                            'project' => $stagedAllocation->project->name,
                            'start' => $stagedAllocation->allocation_date,
                            'end' => $stagedAllocation->allocation_date,
                            'resource' => $stagedAllocation->resource_type,
                            'old_ftes' => $allocation->fte,
                            'new_ftes' => $stagedAllocation->fte,
                        ];
                    }
                }
            } else {
                $lastChange = end($changes);

                if ($lastChange && $lastChange['project'] === $stagedAllocation->project->name &&
                    $lastChange['resource'] === $stagedAllocation->resource_type &&
                    $lastChange['new_ftes'] === $stagedAllocation->fte &&
                    Carbon::parse($lastChange['end'])->addMonth()->isSameDay(Carbon::parse($stagedAllocation->allocation_date))) {
                    
                    $changes[key($changes)]['end'] = $stagedAllocation->allocation_date;
                } else {
                    $changes[] = [
                        'project' => $stagedAllocation->project->name,
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
        } elseif ($action === 'Reject') {
            // Handle rejection logic for Demand
            // Example: Remove or ignore changes
            Log::info("rejected demand: " . print_r($change, true));
        }
    } elseif ($type === 'Allocation') {
        if ($action === 'Accept') {
            // Handle acceptance logic for Allocation
            // Example: Update Allocation model with new data
        } elseif ($action === 'Reject') {
            // Handle rejection logic for Allocation
            // Example: Remove or ignore changes
        }
    }

    // Return response, e.g., redirect or JSON response
    return response()->json(['status' => 'success', 'message' => 'Action processed successfully.']);
}

}
