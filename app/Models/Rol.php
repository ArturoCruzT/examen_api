<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    public $table = 'rol';
    public $fillable = ['nombre', 'activo'];

//----Relaciones------
    public function usuarios() {
        return $this->hasMany('App\Models\Usuario', 'rol_id');
    }


//------Funciones------
    public static function guardarRol($arrInfo) {
        $rol = isset($arrInfo['id']) ? self::find($arrInfo['id']) : new self();
        $rol->fill($arrInfo);
        $rol->save();
        return $rol;
    }
    public static function eliminarRol($arrInfo) {
        $cat = self::find($arrInfo['id']);
        $cat->delete();
        return $cat;
    }

}
