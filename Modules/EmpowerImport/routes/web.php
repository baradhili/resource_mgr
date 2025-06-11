<?php

use Illuminate\Support\Facades\Route;
use Modules\EmpowerImport\App\Http\Controllers\EmpowerImportController;

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

// Before, this route was publicly reachable with no auth or access control:
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::post('/import/empower', [EmpowerImportController::class, 'importEmpower'])
        ->name('import.empower');   // kebab-case for consistency
});
