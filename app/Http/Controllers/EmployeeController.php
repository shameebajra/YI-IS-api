<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function create()
    {
        try {
            //read csv file
            $fileContents = $this->readCsv();

            //convert to array
            $rows = explode("\n", $fileContents);

            foreach ($rows as $row) {
                //convert to array
                $rowVals = explode(',', $row);

                if (isset($rowVals[0], $rowVals[2])) {
                    $email = $this->generateEmailAddress($rowVals[0], $rowVals[2]);
                    $name = $this->generateName($rowVals[0], $rowVals[2]);
                    $roleId = $this->getMappedRole($rowVals[2])->id;
                    $password = $this->generatePassword($rowVals[0]);
                    $randomGender = rand(0, 2);

                    $this->makeEmployee($email, $name, $password, $roleId, $randomGender);
                }
            }
            return response()->json(["message" => "Employee Created Successfully."], 201);
        } catch (Exception $e) {
            logger($e);
            Log::error('Error: ' . $e->getMessage());
        }
    }

    public function readCsv()
    {
        try {
            $csvContents = Storage::disk('data')->get('users.csv');
            return $csvContents;
        } catch (Exception $e) {
            Log::error('Error reading file: ' . $e->getMessage());
        }
    }

    function generateEmailAddress(string $firstName, string $lastName)
    {
        $randomNumber = rand(1, 100);
        return $firstName . '.' . $lastName . $randomNumber . '@gmail.com';
    }

    function generateName(string $firstName, string $lastName)
    {
        return $firstName . " " . $lastName;
    }

    function getMappedRole()
    {
        return Role::inRandomOrder()->first();
    }

    function generatePassword(string $firstname)
    {
        $dummyPassword = $firstname . "123";

        return bcrypt($dummyPassword);
    }

    function makeEmployee($email, $name, $password, $roleId, $randomGender)
    {
        $employee = new User();
        $employee->name = $name;
        $employee->email = $email;
        $employee->password = $password;
        $employee->gender = Gender::ALL[$randomGender];
        $employee->join_date = Carbon::now();
        $employee->role_id = $roleId;

        $employee->created_at = Carbon::now();
        $employee->updated_at = Carbon::now();
        $employee->save();
    }

    public function getAllEmployees()
    {
        try {
            $employees = User::all();
            return $employees;
        } catch (Exception $e) {
            logger($e);
            Log::error('Error: ' . $e->getMessage());
        }
    }

    public function getEmployee($id)
    {
        try {
            $employee = User::findOrFail($id);
            return $employee;
        } catch (Exception $e) {
            logger($e);
            Log::error('Error: ' . $e->getMessage());
        }
    }


    public function updateEmployee(Request $request, $id)
    {
        try {
            $employee = User::findOrFail($id);
            $updatable = $this->getUpdatables($request->toArray());
            $employee->update($updatable);

            return response()->json(["message" => "Successfully employee record updated."]);
        } catch (Exception $e) {
            logger($e);
            Log::error('Error: ' . $e->getMessage());
            return response()->json(["message" => "Unsuccessfull employee record update."]);
        }
    }

    public function getUpdatables($requestValues)
    {
        $updatableKeys = [
            'name',
            'password',
            'gender',
            'join_date',
            'role_id',
        ];

        $returnValue = [];

        foreach ($updatableKeys as $updatableValue) {
            if (array_key_exists($updatableValue, $requestValues)) {
                $returnValue[$updatableValue] = $requestValues[$updatableValue];
            }
        }
        return $returnValue;
    }

    public function deleteEmployee($id)
    {
        try {
            $employee = User::findOrFail($id);

            $employee->delete();

            return response()->json(["message" => "Employee record deleted successfully"]);
        } catch (Exception $e) {
            logger($e);
            Log::error('Error: ' . $e->getMessage());
        }
    }



}
