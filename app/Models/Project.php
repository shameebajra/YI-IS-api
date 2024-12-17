<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'year_of_start',
        'is_domestic'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'employee_projects','project_id','employee_id','id','id');
    }
}


