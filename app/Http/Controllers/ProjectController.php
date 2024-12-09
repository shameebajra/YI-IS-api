<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $projects = Project::all();

            return $projects;
        }catch (Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to fetch project record."
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
        DB::beginTransaction();
        try {
            $employeeIds= $request->employee_id;

            // Validate employee IDs
            if (!is_array($employeeIds) || empty($employeeIds)) {
                return response()->json([
                    "message" => "Employee IDs must be a non-empty array."
                ], 400);
            }

            $employees = User::whereIn('id', $employeeIds)->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    "message" => "No employees exist with the provided IDs."
                ], 404);
            }

            $project = Project::create([
                'name' => $request->name,
                'year_of_start' => $request->year_of_start,
                'is_domestic' => $request->is_domestic,
            ]);

            $project->user()->attach($employeeIds);

            DB::commit();

            return response()->json([
                "message" => "Project created and employees assigned successfully.",
                "project" => $project,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating employee: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to update employee record."
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $projects = Project::findOrFail($id);

            return $projects;
        }catch (Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());

            return response()->json([
                "message" => "Failed to fetch project record."
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        DB::beginTransaction();
        try{
            $employeeIds= $request->employee_id;

            $employees = User::whereIn('id', $employeeIds)->get();

            if ($employees->isEmpty()) {
                return response()->json([
                    "message" => "No employees exist with the provided IDs."
                ], 404);
            }

            $project = Project::findOrFail($id);

            $updatable = $this->getUpdatables($request->toArray());
            $project->update($updatable);

            $project->user()->sync($employeeIds);

            DB::commit();


            return response()->json([
                "message" => "Employee record updated successfully."
            ], 200);
        }catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating projects: ' . $e->getMessage());

            return response()->json([
                "message" => "Project not found or error updating."
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          try{
            $project = Project::findOrFail($id);

            $project->user()->detach();

            return response()->json([
                "message" => "Project deleted successfully"
            ],200);
        } catch (Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());

            return response()->json([
                "message" => "Project not found or unable to delete."
            ], 404);
        }
    }

    public function getUpdatables($requestValues){
        $updatableKeys = [
            'name',
            'year_of_start',
            'is_domestic',
        ];

        $returnValue = [];

        foreach ($updatableKeys as $updatableValue) {
            if (array_key_exists($updatableValue, $requestValues)) {
                $returnValue[$updatableValue] = $requestValues[$updatableValue];
            }
        }

        return $returnValue;
    }
}
