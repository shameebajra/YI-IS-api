<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\TableNames;
use App\Policies\EmployeePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, HasFactory, Notifiable, Authorizable;

    protected $table = TableNames::EMPLOYEES;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'join_date',
        'role_id',
        'updated_at',
        'created_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $policies = [
        User::class=> EmployeePolicy::class,
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    public function project(){
        return $this->belongsToMany(Project::class,'employee_projects','employee_id','project_id','id','id');
    }
}

