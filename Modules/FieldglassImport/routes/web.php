<?php

use Illuminate\Support\Facades\Route;
use Modules\FieldglassImport\App\Http\Controllers\FieldglassImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::post('/import/fieldglass', [FieldglassImportController::class, 'importFieldglass'])
        ->name('import.fieldglass');
});
