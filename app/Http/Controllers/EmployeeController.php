<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CSVService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends CustomResponseController
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
            $limit = $request->query('limit', "25");
            $page = $request->query('page', default: "1");

            $employees = User::with('vehicles','projects')->paginate($limit, ['*'], 'page', $page);

            if ($employees->isEmpty()) {
                return $this->customFailureResponse(404,"No employee found." , ['employees'=>[]] );
            }

            return $this->customSuccessResponse(200,"Employees fetched successful." , ['employees'=>EmployeeResource::collection($employees)] );
        } catch (Exception $e) {
            Log::error('Error fetching employees: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error fetching employees.", [] );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //read csv file from service and create user
            $this->csvService->createUserArr();

            return $this->customSuccessResponse(201,"Employee created successfully." , [] );
        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error creating employees.", [] );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee) //Route Model Binding
    {
      try {
            if (Gate::allows('fetchEmployee', $employee)) {
               $employee = $employee->load('vehicles','projects');

                return $this->customSuccessResponse(200,"Employee fetched successfully." , ['employee'=>new EmployeeResource($employee)] );
            }else{
                return $this->customFailureResponse(403,"You do not have access rights.", [] );
            }
        } catch (Exception $e) {
            Log::error('Error fetching employee: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error fetching employee.", [] );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, User $employee)
    {
        try {
            if (Gate::allows('updateEmployee', $employee)) {
                $updatable = $this->getUpdatables($request->toArray());
                $employee->update($updatable);

                return $this->customSuccessResponse(200,"Employee updated successfully." , ['employee'=>new EmployeeResource($employee)] );
            }else{
                return $this->customFailureResponse(403,"You do not have access rights.", [] );
            }
        } catch (Exception $e) {
            Log::error('Error updating employee: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error updating employee.", [] );
        }
    }

     public function getUpdatables($requestData)
    {
        $updatableFields = [
            'name',
            'password',
            'gender',
            'join_date',
            'role_id',
        ];

        $updatedValues = [];

        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $requestData)) {
                $updatedValues[$field] = $requestData[$field];
            }
        }

        return $updatedValues;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        try {
            if (Gate::allows('deleteEmployee', $employee)) {
                $employee->delete();

                return $this->customSuccessResponse(200,"Employee deleted successfully." , [] );
            }else{
                return $this->customFailureResponse(403,"You do not have access rights.", [] );
            }
        } catch (Exception $e) {
            Log::error('Error deleting employee: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error deleting employee.", [] );
        }
    }

    public function storeEmployees(Request $request){
        try{
            $payload = $request->get('data');
            $employees = Arr::get($payload, 'employees', []);

            User::insert($employees);

            return $this->customSuccessResponse(201,"Employee created successfully." , ['employees'=>EmployeeResource::collection($employees)]);
        } catch (Exception $e) {
            Log::error('Error creating employees: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error creating employees.", [] );
        }
    }

    public  function deleteEmployees(Request $request){
        DB::beginTransaction();
        try{
            $ids = $request->get('ids');

            $idsArray = explode(',', preg_replace('/[\[\] ]/', '', subject: $ids));

            $existingIds =User::whereIn('id', $idsArray)->pluck('id')->toArray();

            $missingIds = array_diff($idsArray, $existingIds);

            if (!empty($missingIds)) {
                return $this->customFailureResponse(404,"The following employee IDs do not exist: " . implode(", ", $missingIds), ["employees"=>[]] );
            }

            if (Gate::allows('bulkDeleteEmployee', [$idsArray])) {
                User::whereIn('id', $idsArray)->delete();

                DB::commit();
                return response()->json([
                        "message" => "Employee records deleted successfully"
                    ],200);
            }else{
                 return $this->customFailureResponse(403,"You do not have access rights.", [] );
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting employees: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error deleting employees.", [] );
        }
    }
}
