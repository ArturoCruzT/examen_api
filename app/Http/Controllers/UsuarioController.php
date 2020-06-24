<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class UsuarioController extends Controller
{

    public function getMultiAllGenerico() {
        try {
            $peticiones = request()->get('peticiones');
            if ($peticiones == null || !is_array($peticiones))
                throw  new Exception('No se recibió el arreglo de peticiones o no es de tipo arreglo');
            $responses = $this->consultaGenericos($peticiones);
            return parent::returnJsonSuccess($responses);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    private function consultaGenericos($peticiones) {
        $responses = [];
        foreach (count($peticiones) > 0 ? $peticiones : [] as $peticion) {
            $response = ['status' => 'pend', 'data' => [], 'nombre' => $peticion['nombre']];
            try {
                $registros = $this->consultaGenerico($peticion['nombre'], $peticion['relaciones']);
                $response['status'] = 'success';
                $response['data'] = $registros;
            } catch (Exception $ex) {
                Log::debug($ex->getTraceAsString());
                $response['status'] = 'error';
                $response['data'] = [];
            }
            $responses[$peticion['nombre']] = $response;
        }
        return $responses;
    }

//|------Genéricos individuales------|//
    public function getAllGenerico($clave) {
        try {
            $relaciones = request('relaciones') != null && count(request('relaciones')) > 0 ? request('relaciones') : [];
            $registros = $this->consultaGenerico($clave, $relaciones);
            return parent::returnJsonSuccess($registros);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function consultaGenerico($clave, $relaciones) {
        $key = $this->camposPorClave($clave);
        $this->verificarKey($clave, $key, 'all');
        $registros = call_user_func('App\Models\\' . $key['modelo'] . '::orderBy', $key['order_by']);
        $relaciones != null && count($relaciones) > 0 ? $registros = $registros->with($relaciones) : null;
        return $registros->get();
    }

    public function getGenerico($clave) {
        try {
            $key = $this->camposPorClave($clave);
            $this->verificarKey($clave, $key, 'get');
            if (request()->get('id') == null)
                throw new Exception(trans('excepciones.propiedadNoEncontrada', ['nombre' => 'id']));
            $id = request()->get('id');
            $registro = call_user_func('App\Models\\' . $key['modelo'] . '::where', 'id', $id);
            request('relaciones') !== null ? $registro = $registro->with(request('relaciones')) : null;
            return parent::returnJsonSuccess($registro->first());
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function guardarGenerico($clave) {
        try {
            $key = $this->camposPorClave($clave);
            $this->verificarKey($clave, $key, 'guardar');
            $info = request()->all();
            return parent::returnJsonSuccess(
                call_user_func('App\Models\\' . $key['modelo'] . '::guardar' . $key['modelo'], $info)
            );
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function eliminarGenerico($clave) {
        try {
            $key = $this->camposPorClave($clave);
            $this->verificarKey($clave, $key, 'guardar');
            $info = request()->all();
            return parent::returnJsonSuccess(
                call_user_func('App\Models\\' . $key['modelo'] . '::eliminar' . $key['modelo'], $info)
            );
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function verificarKey($clave, $key, $metodo) {
        if ($key['modelo'] == null)
            throw new Exception(trans('excepciones.claveNoExiste', ['clave' => $clave]));
        if (!$key[$metodo])
            throw new Exception(trans('excepciones.metodoNoPermitido', ['metodo' => $metodo, 'modelo' => $key['modelo']]));
    }

    public function camposPorClave($clave) {
        $arr = [
            'usuario' => ['modelo' => 'Usuario', 'order_by' => 'nombre', 'all' => true, 'get' => true, 'guardar' => true, 'eliminar'=>true],
            'rol' => ['modelo' => 'Rol', 'order_by' => 'nombre', 'all' => true, 'get' => true, 'guardar' => true, 'eliminar'=>true]
        ];
        if (!isset($arr[$clave]))
            throw new Exception(trans('excepciones.claveNoExiste', ['clave' => $clave]));
        return $arr[$clave];
    }
}
