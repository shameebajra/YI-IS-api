<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\User;
use App\Models\Role;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Enums\Gender;


class EmployeeController extends Controller
{
       /**
     * Read csv file from storage
     */
    public function readCsv()
    {
        try {
            $csvContents = Storage::disk('data')->get('users.csv');

            return $csvContents;
        } catch (Exception $e) {
            Log::error('Error reading file: ' . $e->getMessage());

            return response()->json([
                "message" => "CSV file not found or unable to read."
            ], 404);

        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = User::paginate(25);

            if ($employees->isEmpty()) {
                return response()->json([
                    "message" => "No employees found."
                ], 404);
            }

            return response()->json($employees);
        } catch (Exception $e) {
            Log::error('Error fetching employees: ' . $e->getMessage());

            return response()->json([
                "message" => "An error occurred while retrieving employee data."
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

            return response()->json([
                "message" => "Employee created successfully."
            ], 201);

        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to create employee records."
            ], 500);
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

    function makeEmployee(string $email, string $name, string $password, int $roleId, int $randomGender)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $employee = User::findOrFail($id);

            return $employee;
        } catch (Exception $e) {
            Log::error('Error fetching employee: ' . $e->getMessage());

            return response()->json([
                "message" => "Employee not found."
            ], 404);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id)
    {
        try {
            $employee = User::findOrFail($id);

            $updatable = $this->getUpdatables($request->toArray());
            $employee->update($updatable);

            return response()->json([
                "message" => "Employee record updated successfully."
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating employee: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to update employee record."
            ], 500);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = User::findOrFail($id);

            $employee->delete();

            return response()->json([
                "message" => "Employee record deleted successfully"
            ],200);
        } catch (Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());

            return response()->json([
                "message" => "Employee not found or unable to delete."
            ], 404);
        }
    }

    public function storeEmployees(Request $request){
        try{

            $employees = $request->all();

            foreach($employees as $employee){
                User::create(
                    [
                        'name' => $employee['name'],
                        'email' => $employee['email'],
                        'join_date' => $employee['join_date'],
                        'password' => bcrypt($employee['password']),
                        'role_id' => $employee['role_id'],
                        'gender' => $employee['gender'],
                    ]);
            }

            return response()->json([
                "message" => "Employees created successfully."
            ], 201);

        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to create employees records."
            ], 500);
        }
    }
    public  function deleteEmployees(string $ids){
        try{
            $idsArray = explode(',',$ids);
            User::whereIn('id',$idsArray)->delete();

            return response()->json([
                    "message" => "Employee records deleted successfully"
                ],200);
        } catch (Exception $e) {
            Log::error('Error deleting employees: ' . $e->getMessage());

            return response()->json([
                "message" => "Employees not found or unable to delete."
            ], 404);
        }
    }
}
