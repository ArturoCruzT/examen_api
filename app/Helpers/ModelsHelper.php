<?php

namespace App\Helpers;

class ModelsHelper {

    public static function preparaParaGuardar($info, $propiedades_revisar) {
        foreach (count($propiedades_revisar) > 0 ? $propiedades_revisar : [] as $prop) {
            $nombre_objeto = substr($prop, 0, strlen($prop) - 3);
            $clave_id = $prop;
            if (isset($info[$nombre_objeto]) && isset($info[$nombre_objeto]['id'])) {//Revisando el objeto
                $info[$clave_id] = $info[$nombre_objeto]['id'];
            } elseif (isset($info[$clave_id])) {
                $info[$clave_id] = isset($info[$clave_id]) ? $info[$clave_id] : null;
            }
        }
        return $info;
    }

    public static function eliminarNoVigentes($nuevos, $existentes, $pivote_nuevo, $pivote_existente) {
        $ids_nuevos = array_map(function ($item) use ($pivote_nuevo) { return isset($item[$pivote_nuevo]) ? $item[$pivote_nuevo] : null; }, $nuevos);
        foreach (count($existentes) > 0 ? $existentes : [] as $existente){
            $existente_arr = json_decode(json_encode($existente), true);
            !in_array($existente_arr[$pivote_existente], $ids_nuevos) ? $existente->delete() : null;
        }

    }
}
