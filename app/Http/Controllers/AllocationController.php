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

class AllocationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $allocations = Allocation::paginate();

        return view('allocation.index', compact('allocations'))
            ->with('i', ($request->input('page', 1) - 1) * $allocations->perPage());
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

            //collect up teh dates in row 5


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
                            return redirect()->back()->with('failure', 'Missing resource: '.$resourceName);
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
                                        'year' => substr($monthYear[$i], 0, 4),
                                        'month' => substr($monthYear[$i], 5, 2)
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
            // Read XLSX-file
            // $result['#1'] = $excel
            //     // select sheet by name
            //     ->selectSheet('Dataset_Empower') 
            //     ->readCells();
            //     // select area with data where the first row contains column keys
            //     // ->setReadArea('B4:D11', true)  
            //     // set date format
            //     // ->setDateFormat('Y-m-d') 
            //     // set key for column 'C' to 'Birthday'
            //     // ->readRows(['C' => 'Birthday']); 
            // Log::info("read" . print_r($result, true));
        }
        // $startYear = $request->input('start_year');
        // $startMonth = $request->input('start_month');
        // $endYear = $request->input('end_year');
        // $endMonth = $request->input('end_month');
        // $resourceId = $request->input('resource_id');
        // $projectId = $request->input('project_id');
        // $fte = $request->input('fte');

        // $startDateTime = new \DateTime($startYear . '-' . $startMonth . '-01');
        // $endDateTime = new \DateTime($endYear . '-' . $endMonth . '-01');

        // while ($startDateTime <= $endDateTime) {
        //     $year = $startDateTime->format('Y');
        //     $month = $startDateTime->format('m');

        //     $allocation = new Allocation();
        //     $allocation->year = $year;
        //     $allocation->month = $month;
        //     $allocation->fte = $fte;
        //     $allocation->resources_id = $resourceId;
        //     $allocation->projects_id = $projectId;
        //     $allocation->status = 'pending'; // or any default status you prefer
        //     $allocation->save();

        //     $startDateTime->modify('first day of next month');
        // }

        return redirect()->back()->with('success', 'Allocations populated successfully.');
    }
}
