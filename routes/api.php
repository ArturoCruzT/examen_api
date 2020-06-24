<?php

use Illuminate\Http\Request;

Route::group(['prefix'=>'usuarios'], function(){
    Route::post('getMultiAllGenerico', ['uses'=>'UsuarioController@getMultiAllGenerico']);
    Route::post('getAllGenerico/{clave}', ['uses'=>'UsuarioController@getAllGenerico']);
    Route::post('getGenerico/{clave}', ['uses' => 'UsuarioController@getGenerico']);
    Route::post('guardarGenerico/{clave}', ['uses' => 'UsuarioController@guardarGenerico']);
    Route::post('eliminarGenerico/{clave}', ['uses' => 'UsuarioController@eliminarGenerico']);
});

Route::group(['prefix'=>'documentos'], function(){
    Route::post('guardarFotoEmpleado', ['uses'=>'DocumentoController@guardarFotoEmpleado']);
    Route::post('guardarCvEmpleado', ['uses'=>'DocumentoController@guardarCvEmpleado']);
    Route::post('eliminarDocumento', ['uses'=>'DocumentoController@eliminarDocumento']);
    Route::get('descargarDocumento/{adjunto_id}', ['uses'=>'DocumentoController@descargarDocumento']);
});
