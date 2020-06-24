<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelRolUsuario extends Model
{
    public $table = 'rel_rol_usuario';
    public $fillable = ['rol_id', 'usuario_id'];

//----Relaciones------
    public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id');
    }

    public function rol() {
        return $this->belongsTo('App\Models\Rol', 'rol_id');
    }

//------Funciones------
    public static function guardarRelRolUsuario($arrInfo) {
        $rol = isset($arrInfo['id']) ? self::find($arrInfo['id']) : new self();
        $rol->fill($arrInfo);
        $rol->save();
        return $rol;
    }
}
