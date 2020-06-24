<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response as HttpResponse;
use mysql_xdevapi\Exception;

class LoginController extends Controller
{
    public  function inicioSesion(){
        try {
       $credentials = $this->validate(request(),[
           'email' => 'email|required|string',
           'password' => 'required|string'
        ]);
       if(Auth::attempt($credentials)){
           $user =Auth::login($credentials);
           return self::retornaStatus($user, HttpResponse::HTTP_OK);
       }
        } catch (Exception $ex) {
            Log::error($ex->getFile() . " " . $ex->getLine() . " " . $ex->getMessage() . "\n" . $ex->getTraceAsString());
            $mensaje = 'error';
            return self::retornaStatus(compact('mensaje'), HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(){
        try {
            return  parent::returnJsonSuccess(Auth::check());
        }catch (Exception $ex){
            return $ex;
        }
    }

    private static function retornaStatus($data, $status) {
        return compact('data', 'status');
    }
}
