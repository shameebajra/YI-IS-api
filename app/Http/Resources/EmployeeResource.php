<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'employee_id'=> $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->getGender(),
            'join_date'=> $this->join_date,
            'position' => $this->getPosition(),
            'vehicles' => $this->whenLoaded('vehicles', function () {
                return $this->vehicles;
            }),
            'projects' => $this->whenLoaded('projects', function () {
                return $this->projects;
            }),
        ];
    }

    private function getGender()
    {
        if($this->gender === "F"){
            $gender= 'Female';
         }elseif($this->gender === "M"){
             $gender=  'Male';
         }else{
             $gender=  'Others';
         }

         return $gender;
    }

    private function getPosition(){
        if($this->role_id == 1){
            return 'HR';
        }elseif($this->role_id == 2){
            return 'PM';
        }
        elseif($this->role_id == 3){
            return 'SEII';
        }elseif($this->role_id == 4){
            return 'SEI';
        }else{
            return 'Intern';
        }
    }
}
