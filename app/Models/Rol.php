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


    public function guardarUsuarios($rels_roles) {
        $ids_nuevos = array_map(function($rel_nueva){ return $rel_nueva['username']['id']; }, $rels_roles);
        foreach (count($this->rels_roles) > 0 ? $this->rels_roles : [] as $rel_existente)
            !in_array($rel_existente->username_id, $ids_nuevos) ? $rel_existente->delete() :null;
        foreach (count($rels_roles) > 0 ? $rels_roles : [] as $rel_nueva)
            UsernameRol::guardarRelRol(['rol_id'=>$this->id, 'usuario_id'=>$rel_nueva['usuario']['id']]);
    }

}
