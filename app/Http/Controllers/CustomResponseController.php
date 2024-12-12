<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomResponseController extends Controller
{
    public function customSuccessResponse($status, $message, $data){
        return response()->json([
            "success" => "true",
            "message" => $message,
            "data" =>$data,
        ], $status);
    }

    public function customFailureResponse($status, $message, $data){
        return response()->json([
            "success" => "false",
            "message" => $message,
            "error" =>$data,
        ], $status);
    }
}
