<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class EmployeeService {
    protected $table = 'employees';
    protected $perPage = 10;

    public function fetchEmployees(){
        try{
            $employees = DB::table($this->table)->orderBy('created_at', 'desc')->paginate($this->perPage);
            foreach ($employees as $employee){
                $joiningDate = Carbon::parse($employee->joining_date);
                $date = $joiningDate->diff(Carbon::now());
                if($date->y >= 5 && $employee->status == 1){
                    $employee->is_active = true;
                }else{
                    $employee->is_active = false;
                }
                $employee->joining_date = Carbon::parse($employee->joining_date)->format('M d, Y');
            }
            return $employees;
        }catch(Exception $e) {
            \Log::error('DB Fetch Error: '.$e->getMessage());
            throw $e;
        }
    }

    public function fetchById($id){
       try{
        $employee = DB::table($this->table)->where('id', $id)->first();
        $employee->joining_date = Carbon::parse($employee->joining_date)->format('M d, Y');
        if (!$employee) {
            throw new Exception("Employee not found");
        }
        return $employee;
       }catch(Exception $e){
        \Log::error('DB Fetch Error: '.$e->getMessage());
        throw $e;
       }
    }

    public function insertEmployee($data){
        try{
            $is_exist = DB::table($this->table)->where('email', $data['email'])->orWhere('phone_no', $data['phone_no'])->first();
            if($is_exist){
                throw new Exception('Email or Phone number already exists');
            }
            $formattedDate = Carbon::parse($data['joining_date'])->format('Y-m-d');
            $id = DB::table($this->table)->insertGetId([
                'name' => $data['name'],
                'phone_no' => $data['phone_no'],
                'email' => $data['email'],
                'designation' => $data['designation'],
                'joining_date' => $formattedDate,
                'salary' => $data['salary'],
                'age' => $data['age'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return DB::table($this->table)->where('id', $id)->first();
        }catch (Exception $e){
            \Log::error('Create Employee Error: '.$e->getMessage());
            throw $e;  
        }
    }

    public function search($name){
        try{
            $employees = DB::table($this->table)
            ->where('name', 'LIKE', "%$name%")
            ->orWhere('email', 'LIKE', "%$name%")
            ->orWhere('phone_no', 'LIKE', "%$name%")
            ->orWhere('designation', 'LIKE', "%$name%")
            ->get();
            foreach ($employees as $employee){
                $joiningDate = Carbon::parse($employee->joining_date);
                $date = $joiningDate->diff(Carbon::now());
                if($date->y >= 5 && $employee->status == 1){
                    $employee->is_active = true;
                }else{
                    $employee->is_active = false;
                }
                $employee->joining_date = Carbon::parse($employee->joining_date)->format('M d, Y');
            }
            return $employees;
        }catch(Exception $e){
            \Log::error('DB Fetch Error: '.$e->getMessage());
            throw $e;
        }
    }
}