<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\VehicleResource;
use App\Models\User;
use App\Models\Vehicle;
use App\Traits\CustomResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    use CustomResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $vehicles = Vehicle::with('user')->get();

            if($vehicles->isEmpty()){
                return $this->customFailureResponse(404,"No vehicle found.", ["vehicles"=>[]] );
            }
            return $this->customSuccessResponse(200,"Vehicles fetched successfully." , ["vehicles"=>VehicleResource::collection($vehicles)] );
        }catch(Exception $e){
            Log::error('Error fetching vehicles: ' . $e->getMessage());

            return $this->customFailureResponse(500,"An error occurred while retrieving vehicles data.", "" );
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $id = $request->employee_id;

            $employee = User::find($id);

            if(!$employee){
                return $this->customFailureResponse(404,"No employee found.", ["employees"=>[]] );
            }

            $vehicle = new Vehicle();

            $vehicle->employee_id = $id;
            $vehicle->number_plate = $request->number_plate;
            $vehicle->vehicle_info = json_encode($request->vehicle_info);

            $vehicle->save();

            return $this->customSuccessResponse(201,"Vehicle created successfully." , ["vehicle"=>new VehicleResource($vehicle)]);
        }catch(Exception $e){
            Log::error('Error creating vehicle: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Failed to create vehicle record.", "" );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        try{
            return $this->customSuccessResponse(200,"Vehicle fetched successfully." , ["vehicle"=>new VehicleResource($vehicle)] );
        }catch(Exception $e){
            Log::error('Error fetching vehicles: ' . $e->getMessage());

            return $this->customFailureResponse(500,"An error occurred while retrieving vehicle data.", "" );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try{
            $updatable = $this->getUpdatables($request->toArray());
            $vehicle->update($updatable);

            return $this->customSuccessResponse(200,"Vehicle updated successfully." , ["vehicle"=>new VehicleResource($vehicle)]);
        }catch(Exception $e){
            Log::error('Error updating vehicles: ' . $e->getMessage());

            return $this->customFailureResponse(500,"An error occurred while updating vehicle data.", "" );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        try{
            $vehicle->delete();

            return $this->customSuccessResponse(200,"Vehicle deleted successfully." , "" );
        }catch(Exception $e){
            Log::error('Error deleting vehicles: ' . $e->getMessage());

            return $this->customFailureResponse(500,"An error occurred while deleting vehicle data.", "" );
        }
    }

    public function getUpdatables($requestValues){
        $updatableKeys =[
            'number_plate',
            'vehicle_info',
        ];

        $returnValue = [];

        foreach($updatableKeys as $updatableValue){
            if(array_key_exists($updatableValue, $requestValues)){
                $returnValue[$updatableValue] = $requestValues[$updatableValue];
            }
        }

        return $returnValue;
    }
}
