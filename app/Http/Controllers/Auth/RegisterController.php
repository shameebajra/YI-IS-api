<?php
declare(strict_types =1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
   public function register(Request $request){
    try{
        User::create([
            'email' => $request->email,
            'name'=> $request->name,
            'password' => Hash::make($request->password),
            'gender'=> $request->gender,
            'join_date'=> $request->join_date,
            'role'=> $request->role,
        ]);
        return ("Registration Successful.");
    }catch(Exception $e){
        Log::error('Registration error:'. $e);
        return $e;
    }
   }
}
