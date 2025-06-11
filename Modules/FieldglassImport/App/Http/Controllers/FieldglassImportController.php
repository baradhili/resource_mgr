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

    private $columnBillRateStandardTimeRateHr = 'I';

    private $columnBillRateSTHalfDayDay = 'J';

    private $columnCostCenter = 'K';

    private $columnWorkerSupervisor = 'L';
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

            $sheet = $excel->getSheet('report');

        }

        //top row is teh headings :- Worker, Cost Center Code, Worker Start Date, Work Order Start Date, Work Order End Date, Bill Rate [Standard Time Rate/Day], Bill Rate [Standard Time Rate/Hr], Bill Rate [ST_HalfDay/Day], Cost Center, Worker Supervisor
        //For each row while tehre is a value in the Worker column
        //grab the first row
        $headers = $sheet->nextRow();
        foreach ($sheet->nextRow() as $rowNum => $rowData) {
            if ($rowNum == 1)
                continue;

            if (isset($rowData[$this->columnWorker]) && $rowData[$this->columnWorker] != null) { // Ignore empty lines
                $worker = $rowData[$this->columnWorker];
                //worker is in format lastname, firstnames - but empower uses lastname; firstnames - replace comma with semicolon
                $worker = str_replace(',', ';', $worker);
                //find Resource
                $resource = Resource::where('empowerID', $worker)->first();
                if ($resource != null) {

                    //find current contract for resource
                    $contract = Contract::where('resources_id', $resource->id)->first();
                    if ($contract != null) {
                        $importWorkerStart = (new DateTime())->setTimestamp($rowData[$this->columnWorkerStartDate])->format('Y-m-d');
                        $importWorkerEnd = (new DateTime())->setTimestamp($rowData[$this->columnWorkOrderEndDate])->format('Y-m-d');
                        //check if contract dates are correct
                        if ($contract->start_date != $importWorkerStart) {
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
                        if ($contract->end_date != $importWorkerEnd) {
                            ChangeRequest::create([
                                'record_type' => Contract::class,
                                'record_id' => $contract->id,
                                'field' => 'start_date',
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
