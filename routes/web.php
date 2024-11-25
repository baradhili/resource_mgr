<?php

use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\EstimateController;
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
use App\Http\Controllers\TermsAndConditionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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
    Route::get('/demands-export', [DemandController::class, 'exportDemands'])->name('demands.export');
    Route::resource('demands', DemandController::class);
    Route::resource('leaves', LeaveController::class);
    Route::resource('projects', ProjectController::class);
    Route::get('/resources/{resource}/allocations', [ResourceController::class, 'allocations'])->name('resources.allocations');
    Route::resource('resources', ResourceController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('resource-skills', ResourceSkillController::class);
    Route::get('/users/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::get('/users/settings', [UserController::class, 'settings'])->name('users.settings');
    Route::resource('users', UserController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('estimates', EstimateController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('terms-and-conditions', TermsAndConditionController::class);
    Route::group(['middleware' => ['role:super-admin|admin']], function () {

        Route::resource('permissions', App\Http\Controllers\PermissionController::class);
        Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);

        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
        Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
        Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);

        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

    });
});

