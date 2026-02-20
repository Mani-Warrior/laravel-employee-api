<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use Exception;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService){
        $this->employeeService = $employeeService;
    }

    public function getEmployees(){
        try{
            $employees = $this->employeeService->fetchEmployees();
            return response()->json([
                'status'=>true,
                'data'=>$employees
            ]);
        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch employees',
            ], 500);
        }
    }

    public function getEmployeeDetails($id) {
        try{
            if($id){
                $employee = $this->employeeService->fetchById($id);
                return response()->json([
                    'status'=>true,
                    'data'=>$employee
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Does not having id',
            ], 500);
        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch employee details',
            ], 500);
        }
    }

    public function createEmployee(Request $request) {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_no' => 'required|string|max:15',
                'email' => 'required|email',
                'designation' => 'required|string',
                'joining_date' => 'required|date',
                'salary' => 'required|numeric',
                'age' => 'required|integer',
                'city' => 'required|string',
                'state' => 'required|string',
                'country' => 'required|string',
            ]);

            $employee = $this->employeeService->insertEmployee($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully',
                'data' => $employee
            ], 201);

        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function searchEmployee($name){
        try{
            $employees = $this->employeeService->search($name);
            if ($employees->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No employees found'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'data' => $employees
            ]);
        }catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }
}

