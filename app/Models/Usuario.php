<?php

namespace App\Models;

use App\Helpers\ModelsHelper;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    public $table = 'usuario';
    public $fillable = ['nombre','apellidos','correo','fecha_registro','contraseña','nombre_usuario'];

    //|------Attributes------|//
    //|------Relaciones------|//
    //|------Scopes----------|//
    //|------Funciones de la clase------|//
    //|------Funciones estaticas------|//
    public static function guardarUsuario($arrInfo) {
        $cat = isset($arrInfo['id']) ? self::find($arrInfo['id']) : new self();
        $info_guardar = ModelsHelper::preparaParaGuardar($arrInfo, []);
        $cat->fill($info_guardar);
        $cat->save();
        return $cat;
    }
}
