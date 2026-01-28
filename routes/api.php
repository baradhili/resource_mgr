<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportModulesController;

Route::get('/sidebar/reports', [ReportModulesController::class, 'index'])
    ->name('api.sidebar.reports'); 
