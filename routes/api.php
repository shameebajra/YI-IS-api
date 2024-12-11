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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('/employee',EmployeeController::class)->except('create','edit');

    Route::resource('/vehicle',VehicleController::class)->except('create','edit');

    Route::resource('/project',ProjectController::class)->except('create','edit');

    Route::post(uri: '/employees',action: [EmployeeController::class, 'storeEmployees']);
    Route::delete('/employees', [EmployeeController::class, 'deleteEmployees']);
});

