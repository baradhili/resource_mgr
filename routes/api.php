<?php

use App\Http\Controllers\Api\ReportModulesController;
use Illuminate\Support\Facades\Route;

Route::get('/sidebar/reports', [ReportModulesController::class, 'index'])
    ->name('api.sidebar.reports');
