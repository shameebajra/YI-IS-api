<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'number_plate'=>$this->number_plate,
                'vehicle_info'=> $this->vehicle_info,
                'employee_id'=> $this->employee_id,
        ];
    }
}
