<?php

use App\Http\Controllers\Payroll\AdvanceIssueController;
use App\Http\Controllers\Payroll\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('employee-list', [EmployeeController::class, 'index']);
    Route::post('employee-create', [EmployeeController::class, 'store']);
    Route::post('employee-update/{employee}', [EmployeeController::class, 'update']);

    Route::post('advance-issue', [AdvanceIssueController::class, 'advanceIssue']);
});
