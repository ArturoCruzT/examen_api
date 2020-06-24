<?php

namespace App\Models;

use App\Helpers\ModelsHelper;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{

    public $table = 'documento';
    public $fillable = ['nombre', 'usuario_id', 'extension', 'url', 'tipo'];

    //|------Attributes------|//
    //|------Relaciones------|//
    //|------Scopes----------|//
    //|------Funciones de la clase------|//
    //|------Funciones estaticas------|//
    public static function guardarDocumento($arrInfo) {
        $cat = isset($arrInfo['id']) ? self::find($arrInfo['id']) : new self();
        $info_guardar = ModelsHelper::preparaParaGuardar($arrInfo, []);
        $cat->fill($info_guardar);
        $cat->save();
        return $cat;
    }
}
