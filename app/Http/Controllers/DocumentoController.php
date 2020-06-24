<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Models\Documento;
use Illuminate\Http\Request;
use Storage;
use Exception;
use Log;
use Illuminate\Http\Response as HttpResponse;

class DocumentoController extends Controller
{

    public function guardarCvEmpleado() {
        try {
            $this->guardarDocumentos('CVEM',request()->get('usuario_id'));
            return parent::returnJsonSuccess(['result' => 'ok']);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function guardarFotoEmpleado() {
        try {
            $this->guardarDocumentos('FOEM', request()->get('usuario_id'));
            return parent::returnJsonSuccess(['result' => 'ok']);
        } catch (Exception $ex) {
            return parent::returnJsonError($ex);
        }
    }

    public function guardarDocumentos($tipo,  $usuario_id) {
        foreach (count(request()->file('documentos')) > 0 ? request()->file('documentos') : [] as $indice => $documento) {
            if (get_class($documento) == 'Illuminate\Http\UploadedFile') {
                $nuevo_nombre = "$tipo-" . substr($documento->getClientOriginalName(), -50);
                \Storage::disk('local')->put($nuevo_nombre,  \File::get($documento));
                $documento = Documento::guardarDocumento([
                    'nombre' => $nuevo_nombre,
                    'url' => $nuevo_nombre,
                    'tipo' => $tipo,
                    'usuario_id' => $usuario_id
                ]);

            } else {
                Log::warning("El archivo no es del tipo UploadedFile");
            }
        }
    }

    public function descargarDocumento($documento_id)
    {
        $documento = Documento::find($documento_id);
        if (isset($documento->id)) {
            $fs = Storage::disk('local')->getDriver();
            $file =  $documento->url;
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

    public function eliminarDocumento()
    {
        $documento = Documento::find(request()->get('documento_id'));
        if (isset($documento->id)) {
            FilesHelper::eliminarArchivo($documento->url);
           $documento->delete();
        } else {
            throw new Exception(trans('excepciones.archivoNoEncontrado', ['ruta' => $documento->url]));
        }
    }
}
