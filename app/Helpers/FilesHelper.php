<?php

namespace App\Helpers;

use Exception;
use Log;
use Storage;

/**
 * Helper creado para almacenar, eliminar, sobre-escribir y obtener archivos en
 * el Drobo
 */
class FilesHelper {

    /**
     * Almacena texto en el archivo solicitado
     * @param array $arr_info Propiedades buscadas ['texto', 'nombre_archivo']
     * @return boolean si fue exitoso
     */
    public static function guardarTexto($arr_info) {
        ExcepcionesHelper::validaPropiedades($arr_info, ['texto', 'nombre_archivo']);
        return Storage::disk('drobo')->put(config('gelita.path_almacenamiento') . '/' . $arr_info['nombre_archivo'], $arr_info['texto']);
    }

    /**
     * Copua un archivo al drobo
     * @param array $info Propiedades buscadas ['nombre_actual', 'nombre_nuevo']
     * @return boolean si fue exitoso
     * @throws Exception Si no encuentra el archivo solicitado
     */

    /**
     * Obtiene el texto del nombre de archivo solicitado
     * @param type $nombre Nombre del archiv ocon us respectiva extension
     * @return string devuelve el contenido del archivo
     * @throws Exception si no encuentra el archivo
     * Se desarrollará un sistema de indicadores visuales para los KPI's definido
     */
    public static function getTextoDeArchivo($nombre) {
        $path =  '/' . $nombre;
        if (!Storage::disk('local')->exists($path)) {
            throw new Exception(trans('excepciones.archivoNoEncontrado', ['ruta' => $path]));
        }
        return Storage::disk('local')->get($path);
    }

    /**
     * Elimina el archivo solicitado
     * @param type $nombre Nombre del archivo con us respectiva extension
     * @return boolean devuelve true si fue exitoso
     * @throws Exception si no encuentra el archivo
     */
    public static function eliminarArchivo($nombre) {
        $path =  '/' . $nombre;
        if (!Storage::disk('local')->exists($path)) {
            throw new Exception(trans('excepciones.archivoNoEncontrado'));
        }
        return Storage::disk('local')->delete($path);
    }

    /**
     * Valida si existe un archivo en alguno de los filesystems
     * @param string $path
     * @param string $nombre_disk default = local, puede ser drobo o algun otro
     * @return type
     */
    public static function existeArchivo($path) {
        return Storage::disk($nombre_disk)->exists($path);
    }

}
