<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomResponseController extends Controller
{
    public function customSuccessResponse(int $status,string $message, mixed $data){
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" =>$data,
        ], $status);
    }

    public function customFailureResponse(int $status,string $message,mixed $data){
        return response()->json([
            "success" => false,
            "message" => $message,
            "error" =>$data,
        ], $status);
    }
}
