<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function fetchEmployee(User $user, User $anUser){
        return $user->role->weight <= $anUser->role->weight;
    }

    public function deleteEmployee(User $user){
        return Auth::user()->role_id >=$user->role_id
                                    ? Response::allow()
                                    : Response::deny('Unauthorized access');
    }

    public function updateEmployee(User $user){
        return Auth::user()->role_id >=$user->role_id
                                    ? Response::allow()
                                    : Response::deny('Unauthorized access');
    }
}


