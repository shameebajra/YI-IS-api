<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $vehicles = Vehicle::all();

            if($vehicles->isEmpty()){
                return response()->json([
                    "message" => "No vehicles found."
                ], 404);
            }

            return $vehicles;
        }catch(Exception $e){
            Log::error('Error fetching vehicles: ' . $e->getMessage());

            return response()->json([
                "message" => "An error occurred while retrieving vehicle data."
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
                return response()->json([
                    "message" => "Employee id does not exist."
                ], 404);
            }

            $vehicle = new Vehicle();

            $vehicle->employee_id = $id;
            $vehicle->number_plate = $request->number_plate;
            $vehicle->vehicle_info = json_encode($request->vehicle_info);

            $vehicle->save();

           return response()->json([
                "message" => "Vehicle created successfully."
            ], 201);
        }catch(Exception $e){
            Log::error('Error creating vehicle: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to create vehicle records."
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $vehicle = Vehicle::findOrFail($id);

            return response()->json($vehicle, 200);
        }catch(Exception $e){
            Log::error('Error fetching vehicles: ' . $e->getMessage());

            return response()->json([
                "message" => "Vehicle not found."
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $vehicle = Vehicle::findOrFail($id);

            $updatable = $this->getUpdatables($request->toArray());
            $vehicle->update($updatable);

            return response()->json($vehicle, 200);
        }catch(Exception $e){
            Log::error('Error updating vehicles: ' . $e->getMessage());

            return response()->json([
                "message" => "An error occurred while updating vehicle data."
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $vehicle = Vehicle::findOrFail($id);

            $vehicle->delete();

            return response()->json([
                "message" => "Vehicle deleted successfully."
            ], 200);
        }catch(Exception $e){
            Log::error('Error deleting vehicles: ' . $e->getMessage());

            return response()->json([
                "message" => "An error occurred while deleting vehicle data."
            ], 500);
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
