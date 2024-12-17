<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Traits\CustomResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    use CustomResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $projects = Project::with('users')->get();

            return $this->customSuccessResponse(200,"Projects fetched successful." , ["projects"=>ProjectResource::collection($projects)] );
        }catch (Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Error fetching projects.", [] );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeeIds= $request->employee_id;

            $employees = User::whereIn('id', $employeeIds)->get();

            if ($employees->isEmpty()) {
                return $this->customFailureResponse(404,"No employees exist with the provided IDs.", [] );
            }

            $project = Project::create([
                'name' => $request->name,
                'year_of_start' => $request->year_of_start,
                'is_domestic' => $request->is_domestic,
            ]);

            $project->users()->attach($employeeIds);

            DB::commit();

            return $this->customSuccessResponse(201,"Project created and employees assigned successfully." , ["project"=>new ProjectResource($project)]);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating employee: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Failed to create project.", [] );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        try{
            return $this->customSuccessResponse(200,"Project fetched successully." , ["project"=>new ProjectResource($project)]);
        }catch (Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Failed to fetch project record.", [] );
        }
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
                return $this->customFailureResponse(500,"Error fetching projects.", [ ] );
            }

            $project = Project::findOrFail($id);

            $updatable = $this->getUpdatables($request->toArray());
            $project->update($updatable);

            $project->users()->sync($employeeIds);

            DB::commit();

            return $this->customSuccessResponse(200,"Project and employees updated successfully." , ["project"=>new ProjectResource($project)]);
        }catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating projects: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Project not found or error updating.", [] );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
          try{
            $project->users()->detach();

            return $this->customSuccessResponse(200,"Project deleted successfully." , []);
        } catch (Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());

            return $this->customFailureResponse(500,"Project not found or unable to delete.", [] );
        }
    }

    public function getUpdatables($requestValues){
        $updatableKeys = [
            'name',
            'year_of_start',
            'is_domestic',
        ];

        return Arr::only($requestValues, $updatableKeys);
    }
}
