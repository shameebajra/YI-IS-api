<?php

declare(strict_types=1);

namespace App\Traits;


trait CustomResponseTrait{
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
