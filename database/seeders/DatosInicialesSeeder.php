<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosInicialesSeeder extends Seeder
{
    public function run(): void
    {
        // 👤 CLIENTES
        DB::table('clientes')->insert([
            [
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'dni' => '12345678',
                'telefono' => '111111111',
                'correo' => 'juan@mail.com'
            ],
            [
                'nombre' => 'Maria',
                'apellido' => 'Gomez',
                'dni' => '87654321',
                'telefono' => '222222222',
                'correo' => 'maria@mail.com'
            ]
        ]);

        // 💻 EQUIPOS
        DB::table('equipos')->insert([
            [
                'cliente_id' => 1,
                'marca' => 'HP',
                'modelo' => 'Pavilion',
                'descripcion' => 'Notebook'
            ],
            [
                'cliente_id' => 2,
                'marca' => 'Dell',
                'modelo' => 'Inspiron',
                'descripcion' => 'PC Escritorio'
            ]
        ]);

        // 👨‍🔧 USUARIOS (tecnicos)
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Tecnico 1',
                'email' => 'tec1@mail.com',
                'password' => bcrypt('123456'),
                'rol' => 'tecnico'
            ],
            [
                'nombre' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('123456'),
                'rol' => 'admin'
            ]
        ]);

        // 🔧 REPUESTOS
        DB::table('repuestos')->insert([
            [
                'nombre' => 'Disco SSD',
                'descripcion' => '240GB',
                'precio_base' => 50
            ],
            [
                'nombre' => 'Memoria RAM',
                'descripcion' => '8GB',
                'precio_base' => 30
            ]
        ]);
    }
}