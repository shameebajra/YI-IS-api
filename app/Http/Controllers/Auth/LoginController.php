<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
     public function login(Request $request){
        try{
            $credentials = $request->only("email","password");

            if(Auth::attempt($credentials)){
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->plainTextToken;

                return response()->json([
                    'message' => 'Logged in successfully.',
                    'accessToken' =>$token,
                ], 200);
            } else{
                return response()->json([
                    'message' => 'Invalid credentials.'
                ], 401);
            }
        }catch(Exception $e){
            Log::error('Log in error:'. $e);

            return response()->json([
                'message' => 'Error logging in.'
            ], 401);
        }
     }
}
