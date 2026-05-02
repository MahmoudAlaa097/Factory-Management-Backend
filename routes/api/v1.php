<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ManagementController;
use App\Http\Controllers\Api\V1\DivisionController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\MachineTypeController;
use App\Http\Controllers\Api\V1\MachineController;
use App\Http\Controllers\Api\V1\MachineSectionController;
use App\Http\Controllers\Api\V1\ComponentTypeController;
use App\Http\Controllers\Api\V1\MachineComponentController;
use App\Http\Controllers\Api\V1\FaultController;
use App\Http\Controllers\Api\V1\FaultTechnicianController;
use App\Http\Controllers\Api\V1\FaultComponentController;
use App\Http\Controllers\Api\V1\ComponentReplacementController;
use App\Http\Controllers\Api\V1\DashboardController;

Route::prefix('/v1')->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('managements', ManagementController::class)->only(['index', 'show']);
        Route::apiResource('divisions', DivisionController::class)->only(['index', 'show']);
        Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
        Route::apiResource('machine-types', MachineTypeController::class)->only(['index', 'show']);
        Route::apiResource('machines', MachineController::class)->only(['index', 'show']);
        Route::apiResource('machine-sections', MachineSectionController::class)->only(['index', 'show']);
        Route::apiResource('component-types', ComponentTypeController::class)->only(['index', 'show']);
        Route::apiResource('machine-components', MachineComponentController::class)->only(['index', 'show']);
        Route::apiResource('faults', FaultController::class)->only(['index', 'show', 'store']);

        Route::prefix('faults/{fault}')->group(function () {
            Route::patch('/respond', [FaultController::class, 'respond']);
            Route::patch('/resolve', [FaultController::class, 'resolve']);
            Route::patch('/accept',  [FaultController::class, 'accept']);
            Route::patch('/approve', [FaultController::class, 'approve']);
            Route::patch('/close',   [FaultController::class, 'close']);
            Route::patch('/resolution', [FaultController::class, 'updateResolution']);

            Route::prefix('technicians')->group(function () {
                Route::post('/',              [FaultTechnicianController::class, 'store']);
                Route::delete('{employee}',   [FaultTechnicianController::class, 'destroy']);
            });

            Route::prefix('components')->group(function () {
                Route::post('/',                    [FaultComponentController::class, 'store']);
                Route::delete('{faultComponent}',   [FaultComponentController::class, 'destroy']);
            });

            Route::prefix('replacements')->group(function () {
                Route::get('/',                          [ComponentReplacementController::class, 'index']);
                Route::get('{componentReplacement}',     [ComponentReplacementController::class, 'show']);
                Route::post('/',                         [ComponentReplacementController::class, 'store']);
            });
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('kpis',        [DashboardController::class, 'kpis']);
            Route::get('machines',    [DashboardController::class, 'machines']);
            Route::get('technicians', [DashboardController::class, 'technicians']);
            Route::get('activity',    [DashboardController::class, 'activity']);
        });
    });
