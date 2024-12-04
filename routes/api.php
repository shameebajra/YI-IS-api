<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

Route::controller(EmployeeController::class)->group(function(){
    Route::post('/create','create');
    Route::get('/employees-data','getAllEmployees');
    Route::get('/employee/{id}','getEmployee');
    Route::put('/employee/{id}','updateEmployee');
    Route::delete('/employee/{id}','deleteEmployee');
});



