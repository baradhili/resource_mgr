<?php

use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeRequestController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CapacityController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\DemandRequestController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\FundingApprovalStageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceSkillController;
use App\Http\Controllers\ResourceTypeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SiteController;
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
    Route::get('allocations/editOne', [AllocationController::class, 'editOne'])->name('allocations.editOne');
    Route::resource('allocations', AllocationController::class);
    // additional functions
    Route::get('/contracts/clean', [ContractController::class, 'cleanProjects'])->name('contracts.clean');
    Route::resource('contracts', ContractController::class);
    Route::get('/demands/export', [DemandController::class, 'exportDemands'])->name('demands.export');
    Route::get('/demands/{project}/editFullDemand/{resource_type}', [DemandController::class, 'editFullDemand'])->name('demands.editFullDemand');
    Route::resource('demands', DemandController::class);
    Route::resource('leaves', LeaveController::class);
    Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
    Route::resource('projects', ProjectController::class);
    Route::get('/resources/{resource}/allocations', [ResourceController::class, 'allocations'])->name('resources.allocations');
    Route::resource('resources', ResourceController::class);
    Route::post('/skills/upload', [SkillController::class, 'importRsd'])->name('skills.upload');
    Route::resource('skills', SkillController::class);
    Route::resource('resource-skills', ResourceSkillController::class);
    Route::get('/users/profile/{user}', [UserController::class, 'profile'])->name('users.profile');
    Route::get('/users/settings', [UserController::class, 'settings'])->name('users.settings');
    Route::resource('users', UserController::class);
    // Route::resource('teams', TeamController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('estimates', EstimateController::class);
    Route::resource('clients', ClientController::class);
    Route::get('/capacity/export', [CapacityController::class, 'exportCapacity'])->name('capacity.export');
    Route::resource('capacity', CapacityController::class);
    Route::resource('terms-and-conditions', TermsAndConditionController::class);
    Route::get('change-password', [AuthController::class, 'showChangePasswordForm'])->name('auth.change-password');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password.update');
    Route::middleware('role:super-admin|admin')->group(function () {

        Route::resource('permissions', App\Http\Controllers\PermissionController::class);
        Route::get('permissions/{permissionId}/delete', [App\Http\Controllers\PermissionController::class, 'destroy']);
        Route::resource('plugins', PluginController::class);
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::get('roles/{roleId}/delete', [App\Http\Controllers\RoleController::class, 'destroy']);
        Route::get('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'addPermissionToRole']);
        Route::put('roles/{roleId}/give-permissions', [App\Http\Controllers\RoleController::class, 'givePermissionToRole']);
        Route::get('users/profile/{userId}', [App\Http\Controllers\UserController::class, 'profile']);
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::get('users/{userId}/delete', [App\Http\Controllers\UserController::class, 'destroy']);
        Route::resource('regions', RegionController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('resource-types', ResourceTypeController::class);
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        // Route::post('/import/empower', [ImportController::class, 'populateAllocations'])->name('import.empower');
        // Route::get('/import/review/demands', [ImportController::class, 'reviewDemands'])->name('import.review.demands');
        // Route::get('/import/review/allocations', [ImportController::class, 'reviewAllocations'])->name('import.review.allocations');
        // Route::post('/import/review/actions', [ImportController::class, 'handleReviewAction'])->name('import.review.action');
        // Route::get('/import/holidays', [ImportController::class, 'importHolidays'])->name('import.holidays');
        Route::resource('public-holidays', PublicHolidayController::class);
        Route::resource('sites', SiteController::class);
        Route::resource('domains', DomainController::class);
        Route::resource('funding-approval-stages', FundingApprovalStageController::class);
        Route::resource('requests', DemandRequestController::class);
        Route::get('change-requests/{changeRequest}/approve', [ChangeRequestController::class, 'approve'])->name('change-requests.approve');
        Route::resource('change-requests', ChangeRequestController::class);
    });
});

/**
 * Teamwork routes
 */
Route::prefix('teams')->group(function () {
    Route::get('/', [App\Http\Controllers\Teamwork\TeamController::class, 'index'])->name('teams.index');
    Route::get('create', [App\Http\Controllers\Teamwork\TeamController::class, 'create'])->name('teams.create');
    Route::post('teams', [App\Http\Controllers\Teamwork\TeamController::class, 'store'])->name('teams.store');
    Route::get('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'edit'])->name('teams.edit');
    Route::put('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'update'])->name('teams.update');
    Route::delete('destroy/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('switch/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'switchTeam'])->name('teams.switch');
    Route::get('show/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'show'])->name('teams.show');
    Route::get('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'show'])->name('teams.members.show');
    Route::get('members/resend/{invite_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'resendInvite'])->name('teams.members.resend_invite');
    Route::post('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'invite'])->name('teams.members.invite');
    Route::delete('members/{id}/{user_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
    Route::get('/leader/{team_id}/{user_id}', [App\Http\Controllers\Teamwork\TeamController::class, 'makeLeader'])->name('teams.leader');
    Route::get('accept/{token}', [App\Http\Controllers\Teamwork\AuthController::class, 'acceptInvite'])->name('teams.accept_invite');
});
