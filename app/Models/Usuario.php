<?php

namespace App\Models;

use App\Helpers\ModelsHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Log;

class Usuario extends Model
{
    public $table = 'usuario';
    public $fillable = ['nombre','apellidos','correo','fecha_registro','password','nombre_usuario', 'rol_id'];

    //|------Attributes------|//
    public function rol() {
        return $this->belongsTo('App\Models\Rol', 'rol_id');
    }
    public function documento_foto() {
        return $this->hasOne('App\Models\Documento', 'usuario_id')->where('tipo', 'FOEM');
    }

    public function cv() {
        return $this->hasOne('App\Models\Documento', 'usuario_id')->where('tipo', 'CVEM');
    }
    //|------Relaciones------|//
    //|------Scopes----------|//
    //|------Funciones de la clase------|//
    //|------Funciones estaticas------|//
    public static function guardarUsuario($arrInfo) {
        Log::debug('------------GUARDANDO------------------');
        $cat = isset($arrInfo['id']) ? self::find($arrInfo['id']) : new self();
        if(!isset($cat->id))
            $cat->fecha_registro = Carbon::now();
        $info_guardar = ModelsHelper::preparaParaGuardar($arrInfo, ['rol_id']);
        $cat->fill($info_guardar);
        $cat->save();
        return $cat;
    }

    public static function eliminarUsuario($arrInfo) {
        $cat = self::find($arrInfo['id']);
        $cat->delete();
        return $cat;
    }
}
