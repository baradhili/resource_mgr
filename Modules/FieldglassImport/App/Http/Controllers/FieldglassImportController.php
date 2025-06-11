<?php

namespace Modules\FieldglassImport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use avadim\FastExcelReader\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FieldglassImportController extends Controller
{
    public function importFieldglass(Request $request)
    {
        // upload the file
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $fileName = $uploadedFile->getClientOriginalName();

            // Generate the desired file name
            $currentDate = now()->format('Y-m-d');
            $fileName = "{$currentDate}_upload.xlsx";

            // Store the uploaded file with the generated name
            $path = $uploadedFile->storeAs('uploads', $fileName);
        }

    }
}
