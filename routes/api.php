<?php
declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VehicleController;
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

Route::post('/login',[LoginController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('/employees',EmployeeController::class);

    Route::apiResource('/vehicles',VehicleController::class);

    Route::apiResource('/projects',ProjectController::class);

    Route::post(uri: '/employees/bulk/store',action: [EmployeeController::class, 'storeEmployees']);
    Route::delete('/employees/bulk/delete', [EmployeeController::class, 'deleteEmployees']);
});

