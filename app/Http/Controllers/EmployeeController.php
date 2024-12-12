<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CSVService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{

    protected $csvService;

    public function __construct()
    {
        $this->csvService = new CSVService('users.csv');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 25);
            $page = $request->query('page', default: 1);

            $employees = User::paginate($limit, ['*'], 'page', $page);

            if ($employees->isEmpty()) {
                return response()->json([
                    "message" => "No employees found."
                ], 404);
            }

            return response()->json($employees);
        } catch (Exception $e) {
            Log::error('Error fetching employees: ' . $e->getMessage());

            return response()->json([
                "message" => "Error fetching employees."
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
            $this->csvService->createUserArr();

            return response()->json([
                "message" => "Employee created successfully."
            ], 201);

        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $employee = User::findOrFail($id);
            if (Gate::allows('fetchEmployee', $employee)) {
                return $employee;
            }else{
                return response()->json([
                    "message" => "Unauthorized access"
                ], 403);
            }
        } catch (Exception $e) {
            Log::error('Error fetching employee: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
            ], 500);
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

            if (Gate::allows('updateEmployee', $employee)) {
                $updatable = $this->getUpdatables($request->toArray());
                $employee->update($updatable);

                return response()->json([
                    "message" => "Employee record updated successfully."
                ], 200);
            }else{
                return response()->json([
                    "message" => "Unauthorized access"
                ], 403);
            }

        } catch (Exception $e) {
            Log::error('Error updating employee: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
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
            if (Gate::allows('deleteEmployee', $employee)) {
                $employee->delete();

                return response()->json([
                    "message" => "Employee record deleted successfully"
                ],200);
            }else{
                return response()->json([
                    "message" => "Unauthorized access"
                ], 403);
            }
        } catch (Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
            ], 404);
        }
    }

    public function storeEmployees(Request $request){
        try{
            $payload = $request->get('data');
            $employees = Arr::get($payload, 'employees', []);

            User::insert($employees);

            return response()->json([
                "message" => "Employees created successfully."
            ], 201);

        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public  function deleteEmployees(Request $request){
        DB::beginTransaction();
        try{
            $ids = $request->query('ids');

            // Convert string to array
            $idsArray = explode(',', preg_replace('/[\[\] ]/', '', subject: $ids));

            $existingIds =User::whereIn('id', $idsArray)->pluck('id')->toArray();

            // Check if query IDs exist in the database
            $missingIds = array_diff($idsArray, $existingIds);

            // If there are any missing IDs
            if (!empty($missingIds)) {
                return response()->json([
                    "message" => "The following employee IDs do not exist: " . implode(", ", $missingIds)
                ], 404);
            }


            if (Gate::allows('bulkDeleteEmployee', [$idsArray])) {
                User::whereIn('id', $idsArray)->delete();

                DB::commit();
                return response()->json([
                        "message" => "Employee records deleted successfully"
                    ],200);
            }else{
                return response()->json([
                    "message" => "Unauthorized access"
                ], 403);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting employees: ' . $e->getMessage());

            return response()->json([
                "message" => $e->getMessage()
            ], 404);
        }
    }
}
