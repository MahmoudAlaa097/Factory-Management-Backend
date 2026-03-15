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
    });
