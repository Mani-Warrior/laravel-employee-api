<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('employees', [EmployeeController::class, 'getEmployees']);
Route::get('employee-details/{id}', [EmployeeController::class, 'getEmployeeDetails']);
Route::post('add-employee', [EmployeeController::class, 'createEmployee']);
Route::get('/employees/search/{query}', [EmployeeController::class, 'searchEmployee']);