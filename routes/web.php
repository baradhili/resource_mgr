<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceSkillController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;


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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('allocations', AllocationController::class);
    //additional functions
    Route::post('/allocations-upload', [AllocationController::class, 'populateAllocations'])->name('allocations.upload');

    Route::resource('contracts', ContractController::class);
    Route::resource('demands', DemandController::class);
    Route::get('/demands-export', [DemandController::class, 'exportDemands'])->name('demands.export');
    Route::resource('leaves', LeaveController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('resources', ResourceController::class);
    Route::get('/resources/{resource}/allocations', [ResourceController::class, 'allocations'])->name('resources.allocations');
    Route::resource('skills', SkillController::class);
    Route::resource('resource-skills', ResourceSkillController::class);
    Route::resource('users', UserController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('abilities', AbilityController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('capabilities', CapabilityController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('services', ServiceController::class);
});

