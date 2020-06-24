<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response as HttpResponse;
use Log;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function returnJsonSuccess($data) {
        return response()->json($data, HttpResponse::HTTP_OK);
    }

    public function returnJsonError(Exception $ex) {
        $mensaje = $ex->getMessage();
        $traza = $ex->getFile() . " " . $ex->getLine() . " " . $ex->getMessage() . "\n" . $ex->getTraceAsString();
        Log::error($traza);
        return response()->json(compact('mensaje', 'traza'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function returnJsonArrErrores($errores) {
        return response()->json(compact('errores'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
