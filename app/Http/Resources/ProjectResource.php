<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=> $this->name,
            'year_of_start'=>$this->year_of_start,
            'is_domestic'=>$this->is_domestic,
            'employees' => $this->whenLoaded('users', function () {
                return $this->users;
            }),
        ];
    }
}
