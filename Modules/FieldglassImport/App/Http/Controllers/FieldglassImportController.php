<?php

namespace Modules\FieldglassImport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Contract;
use App\Models\ChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use avadim\FastExcelReader\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DateTime;

class FieldglassImportController extends Controller
{
    // excel columns
    private $columnWorkerID = 'A';

    private $columnWorkOrderID = 'B';

    private $columnWorker = 'C';

    private $columnCostCenterCode = 'D';

    private $columnWorkerStartDate = 'E';

    private $columnWorkOrderStartDate = 'F';

    private $columnWorkOrderEndDate = 'G';

    private $columnBillRateStandardTimeRateDay = 'H';

    private $columnBillRateSTHalfDayDay = 'I';

    private $columnWorkerSupervisor = 'J';

    private $sheetName = 'IS&T_Workers_-_S&A';
    /**
     * Import Fieldglass data into the system. This function
     * reads the uploaded file and stores the data in the database.
     *
     * @param Request $request - the request object containing the uploaded file
     * @return RedirectResponse - redirect back to the calling page with a success message
     */
    public function importFieldglass(Request $request): RedirectResponse
    {
        // upload the file
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

            // Generate the desired file name
            $currentDate = now()->format('Y-m-d');
            $fileName = "{$currentDate}_fieldglass_upload.xlsx";

            // Store the uploaded file with the generated name
            $path = $uploadedFile->storeAs('uploads', $fileName);

            // Open XLSX-file
            $excel = Excel::open(Storage::path($path));

            $sheet = $excel->getSheet($this->sheetName);

        }

        //top row is teh headings :- Worker ID, Work Order ID, Worker, Cost Center Code, Worker Start Date, Work Order Start Date, Work Order End Date, Bill Rate [Standard Time Rate/Day], Bill Rate [ST_HalfDay/Day], Worker Supervisor
        //For each row while tehre is a value in the Worker column
        //grab the first row

        foreach ($sheet->nextRow() as $rowNum => $rowData) {
            if ($rowNum == 1 || $rowNum == 2)
                continue;
            // Log::info(("rowData = ".json_encode($rowData)));

            if (isset($rowData[$this->columnWorker]) && $rowData[$this->columnWorker] != null) { // Ignore empty lines
                $worker = $rowData[$this->columnWorker];
                //worker is in format lastname, firstnames - but empower uses lastname; firstnames - replace comma with semicolon
                $worker = str_replace(',', ';', $worker);
                //find Resource
                $resource = Resource::where('empowerID', $worker)->first();
                // Log::info(("resource = ".json_encode($resource)));
                if ($resource != null) {

                    //find current contract for resource
                    $contract = Contract::where('resources_id', $resource->id)
                        ->orderBy('start_date', 'desc')
                        ->first();
                    if ($contract != null) {
                        // Log:info("contract found");
                        $importWorkerStart = (new DateTime())->setTimestamp($rowData[$this->columnWorkerStartDate])->format('Y-m-d');
                        $importWorkerEnd = (new DateTime())->setTimestamp($rowData[$this->columnWorkOrderEndDate])->format('Y-m-d');
                        $storedContractStart = (new DateTime())->setTimestamp(strtotime($contract->start_date))->format('Y-m-d');
                        $storedContractEnd = (new DateTime())->setTimestamp(strtotime($contract->end_date))->format('Y-m-d');  
                        //check if contract dates are correct
                        if ($storedContractStart != $importWorkerStart) {
                            // Log::info("contract start date incorrect");
                            ChangeRequest::create([
                                'record_type' => Contract::class,
                                'record_id' => $contract->id,
                                'field' => 'start_date',
                                'old_value' => $contract->start_date,
                                'new_value' => $importWorkerStart,
                                'status' => 'pending',
                                // 'requested_by' => 0, // 0 will indicate teh import function - otherwise we put the user id
                            ]);
                        }
                        if ($storedContractEnd != $importWorkerEnd) {
                            ChangeRequest::create([
                                'record_type' => Contract::class,
                                'record_id' => $contract->id,
                                'field' => 'end_date',
                                'old_value' => $contract->end_date,
                                'new_value' => $importWorkerEnd,
                                'status' => 'pending',
                                // 'requested_by' => 0, // 0 will indicate teh import function - otherwise we put the user id
                            ]);
                        }

                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Data staged successfully for further processing.');
    }
}
