<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::resource('allocations', AllocationController::class);
//additional functions
Route::post('/allocations-upload', [AllocationController::class, 'populateAllocations'])->name('allocations.upload');

Route::resource('contracts', ContractController::class);
Route::resource('demands', DemandController::class);
Route::resource('leaves', LeaveController::class);
Route::resource('projects', ProjectController::class);
Route::resource('resources', ResourceController::class);

