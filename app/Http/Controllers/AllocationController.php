<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Resource;
use App\Models\Project;
use App\Models\Demand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AllocationRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use \avadim\FastExcelReader\Excel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AllocationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Build our next twelve month array
        $nextTwelveMonths = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
        }
        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect our resources who have a current contract
        $resources = Resource::whereHas('contracts', function ($query) {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        })->paginate();

        // For each resource - find teh allocations for the period
        foreach ($resources as $resource) {

            $resourceAvailability[$resource->id] = [
                'name' => $resource->full_name,
            ];

            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                // $monthEndDate = $monthStartDate->copy()->endOfMonth();
                $totalAllocation = Allocation::where('allocation_date', '=', $monthStartDate)
                    ->where('resources_id', '=', $resource->id)
                    ->sum('fte');
                // Use year-month as the key
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($totalAllocation > 0) {
                    $resourceAllocation[$resource->id]['allocation'][$key] = $totalAllocation;
                }
            }
        }
// Log::info("Return: " . json_encode($resourceAllocation));
        return view('allocation.index', compact('resources', 'resourceAllocation','nextTwelveMonths'))
            ->with('i', ($request->input('page', 1) - 1) * $resources->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $allocation = new Allocation();
        $resources = Resource::all(); // Retrieve all resources
        $projects = Project::all(); // Retrieve all projects

        return view('allocation.create', compact('allocation', 'resources', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllocationRequest $request): RedirectResponse
    {
        Allocation::create($request->validated());

        return Redirect::route('allocations.index')
            ->with('success', 'Allocation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $allocation = Allocation::find($id);

        return view('allocation.show', compact('allocation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $allocation = Allocation::find($id);
        $allocation = new Allocation();
        $resources = Resource::all(); // Retrieve all resources
        $projects = Project::all(); // Retrieve all projects

        return view('allocation.edit', compact('allocation', 'resources', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AllocationRequest $request, Allocation $allocation): RedirectResponse
    {
        $allocation->update($request->validated());

        return Redirect::route('allocations.index')
            ->with('success', 'Allocation updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Allocation::find($id)->delete();

        return Redirect::route('allocations.index')
            ->with('success', 'Allocation deleted successfully');
    }

    public function populateAllocations(Request $request)
    {
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

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

            //collect up the dates in row 5

            foreach ($sheet->nextRow() as $rowNum => $rowData) {
                if ($rowNum == 5) { //grab header row
                    // step throw columns 'D' on until blank, capture each filled column into array as monthYear
                    $monthYear = [];
                    foreach ($rowData as $columnLetter => $columnValue) {
                        if ($columnLetter >= 'D' && !is_null($columnValue)) {
                            $monthYear[] = $columnValue;
                        }
                    }
                    // Log::info("months " . print_r($monthYear, true));
                }
                if ($rowNum < 6) { //skip first 5 rows
                    continue;
                } elseif ($rowData['B'] != null) { //ignore empty lines
                    $resourceName = $rowData['A'] ?? $resourceName;
                    if (strpos($resourceName, 'rchitect') == false) {

                        $resource = Resource::where('empowerID', $resourceName)->first();
                        $resourceID = $resource->id ?? null;
                        if (is_null($resourceID)) {
                            return redirect()->back()->with('failure', 'Missing resource: ' . $resourceName);
                        }
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

                        // Log::info(message: $resourceName." " . $rowNum ." ". $projectID." ". $projectName);

                        for ($i = 0; $i < count($monthYear); $i++) {
                            $columnLetter = chr(68 + $i); // 'D' + i
                            $fte = is_double($rowData[$columnLetter]) ? (double) $rowData[$columnLetter] : 0.00;
                            if ($fte > 0) {
                                // Log::info("fte " . $monthYear[$i] . " " . $resourceName . " " . $projectID . " " . $projectName . " " . print_r($rowData[$columnLetter], true));

                                $allocation = Allocation::updateOrCreate(
                                    [
                                        'resources_id' => $resourceID,
                                        'projects_id' => $projectID,
                                        'allocation_date' => \Carbon\Carbon::createFromFormat('Y-m', $monthYear[$i])->format('Y-m-d')
                                    ],
                                    [
                                        'fte' => $fte
                                    ]
                                );
                            }

                        }
                    } else { //insert these into demand

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

                        // Log::info(message: $resourceName." " . $rowNum ." ". $projectID." ". $projectName);

                        for ($i = 0; $i < count($monthYear); $i++) {
                            $columnLetter = chr(68 + $i); // 'D' + i
                            $fte = is_double($rowData[$columnLetter]) ? (double) $rowData[$columnLetter] : 0.00;
                            if ($fte > 0) {
                                Log::info("fte " . $monthYear[$i] . " " . $resourceName . " " . $projectID . " " . $projectName . " " . print_r($rowData[$columnLetter], true));


                            }

                        }
                    }

                }
            }
        }

        return redirect()->back()->with('success', 'Allocations populated successfully.');
    }
}
