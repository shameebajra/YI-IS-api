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
            'name' => $this->name,
            'email' => $this->email,
            'gender' =>(function(){
                            if($this->gender === "F"){
                               $gender= 'Female';
                            }elseif($this->gender === "M"){
                                $gender=  'Male';
                            }else{
                                $gender=  'Others';
                            }

                            return $gender;
            })(),
            'join_date'=> $this->join_date,
            'position' => $this->role_id == 10 ? 'Intern' : 'Employee',
            'vehicles' => $this->whenLoaded('vehicles', function () {
                return VehicleResource:: make($this->vehicles)->count();
            }),
        ];
    }
}
