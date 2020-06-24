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
    public static function copiarArchivoFromLocalToCloud($info) {
        ExcepcionesHelper::validaPropiedades($info, ['nombre_actual', 'nombre_nuevo']);
        if (!Storage::disk('tmp')->exists($info['nombre_actual'])) {
            throw new Exception(trans('excepciones.archivoNoEncontrado', ['ruta'=>$info['nombre_actual']]));
        }
        $contenido_local = Storage::disk('tmp')->get($info['nombre_actual']);
        return Storage::disk('drobo')->put(config('gelita.path_almacenamiento') . '/' . $info['nombre_nuevo'], $contenido_local);
    }

    /**
     * Obtiene el texto del nombre de archivo solicitado
     * @param type $nombre Nombre del archiv ocon us respectiva extension
     * @return string devuelve el contenido del archivo
     * @throws Exception si no encuentra el archivo
     * Se desarrollará un sistema de indicadores visuales para los KPI's definido
     */
    public static function getTextoDeArchivo($nombre) {
        $path = config('gelita.path_almacenamiento') . '/' . $nombre;
        if (!Storage::disk('drobo')->exists($path)) {
            throw new Exception(trans('excepciones.archivoNoEncontrado', ['ruta' => $path]));
        }
        return Storage::disk('drobo')->get($path);
    }

    /**
     * Elimina el archivo solicitado
     * @param type $nombre Nombre del archivo con us respectiva extension
     * @return boolean devuelve true si fue exitoso
     * @throws Exception si no encuentra el archivo
     */
    public static function eliminarArchivo($nombre) {
        $path = config('gelita.path_almacenamiento') . '/' . $nombre;
        if (!Storage::disk('drobo')->exists($path)) {
            throw new Exception(trans('excepciones.archivoNoEncontrado'));
        }
        return Storage::disk('drobo')->delete($path);
    }

    /**
     * Valida si existe un archivo en alguno de los filesystems
     * @param string $path 
     * @param string $nombre_disk default = local, puede ser drobo o algun otro
     * @return type
     */
    public static function existeArchivo($path, $nombre_disk = "local") {
        $path_real = $nombre_disk == "drobo" ? config('gelita.path_almacenamiento') . '/' . $path : $path;
        return Storage::disk($nombre_disk)->exists($path_real);
    }

}
