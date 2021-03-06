<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('rol', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 50);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->string('email')->unique();
            $table->date('fecha_registro');
            $table->string('password');
            $table->string('nombre_usuario');
            $table->integer('rol_id')->unsigned();
            $table->foreign('rol_id')->references('id')->on('rol');
            $table->timestamps();
        });
        Schema::create('documento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->string('nombre', 50);
            $table->string('url', 100);
            $table->string('tipo', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
