<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosInicialesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clientes')->insert([
            [
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'dni' => '12345678',
                'telefono' => '111111111',
                'correo' => 'juan@mail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Maria',
                'apellido' => 'Gomez',
                'dni' => '87654321',
                'telefono' => '222222222',
                'correo' => 'maria@mail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('equipos')->insert([
            [
                'cliente_id' => 1,
                'marca' => 'HP',
                'modelo' => 'Pavilion',
                'descripcion' => 'Notebook',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 2,
                'marca' => 'Dell',
                'modelo' => 'Inspiron',
                'descripcion' => 'PC Escritorio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('usuarios')->insert([
            [
                'nombre' => 'Técnico principal',
                'email' => 'tecnico@example.com',
                'password' => bcrypt('password'),
                'rol' => 'tecnico',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Recepción',
                'email' => 'recepcionista@example.com',
                'password' => bcrypt('password'),
                'rol' => 'recepcionista',
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('repuestos')->insert([
            [
                'nombre' => 'Disco SSD',
                'descripcion' => '240GB',
                'precio_base' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Memoria RAM',
                'descripcion' => '8GB',
                'precio_base' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
