<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class DocumentoController extends Controller
{

    public function guardarCvEmpleado() {
        try {
            $this->guardarDocumentos('CVEM');
            return parent::returnJsonSuccess(['result' => 'ok']);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function guardarFotoEmpleado() {
        try {
            $this->guardarDocumentos('FOEM');
            return parent::returnJsonSuccess(['result' => 'ok']);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function guardarDocumentos($tipo, $data = null) {
        foreach (count(request()->file('documentos')) > 0 ? request()->file('documentos') : [] as $indice => $documento) {
            if (get_class($documento) == 'Illuminate\Http\UploadedFile') {
                $nombre_tmp = last(explode('/', last(explode('\\', $documento->path()))));
                $nuevo_nombre = "$tipo-" . substr($documento->getClientOriginalName(), -50);
                $documento = Documento::guardarDocumento([
                    'nombre' => $nuevo_nombre,
                    'url' => $nuevo_nombre,
                    'tipo' => $tipo,
                    'usuario_id' => $usuario_id
                ]);
                FilesHelper::copiarArchivoFromLocalToCloud([
                    'nombre_actual' => $nombre_tmp,
                    'nombre_nuevo' => $documento->id . "-" . $nuevo_nombre
                ]);
            } else {
                Log::warning("El archivo no es del tipo UploadedFile");
            }
        }
    }

    public function descargar($documento_id) {
        $documento = Documento::find($documento_id);
        if (isset($documento->id) && FilesHelper::existeArchivo($documento->id . "-" . $documento->url, 'drobo')) {
            $fs = Storage::disk('drobo')->getDriver();
            $file = config('gelita.path_almacenamiento') . '/' . $documento->id . "-" . $documento->url;
            $stream = $fs->readStream($file);
            return response()->stream(function () use ($stream) {
                fpassthru($stream);
            }, HttpResponse::HTTP_OK, [
                "Content-Type" => $fs->getMimetype($file),
                "Content-Length" => $fs->getSize($file),
                "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
            ]);
        } else {
            throw new Exception(trans('excepciones.archivoNoEncontrado', ['ruta' => $documento->url]));
        }
}
