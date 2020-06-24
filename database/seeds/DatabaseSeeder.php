<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rol')->insert([
            'id'=>1,
            'nombre' => 'Admin',
            'activo' => true
        ]);
        DB::table('rol')->insert([
            'id'=>2,
            'nombre' => 'Empleado',
            'activo' => true
        ]);
        DB::table('users')->insert([
            'nombre' => 'artukok',
            'apellidos' => Str::random(10),
            'nombre_usuario' => Str::random(6),
            'fecha_registro' =>  Carbon::now(),
            'rol_id' => 1,
            'email' => 'artukok@gmail.com',
            'password' => '123456789',
        ]);
        DB::table('users')->insert([
            'nombre' => Str::random(10),
            'apellidos' => Str::random(10),
            'nombre_usuario' => Str::random(6),
            'fecha_registro' =>  Carbon::now(),
            'rol_id' => 2,
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
