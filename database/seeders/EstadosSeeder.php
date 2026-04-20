<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados')->insert([
            ['nombre' => 'Abierto'],
            ['nombre' => 'Asignado'],
            ['nombre' => 'En proceso'],
            ['nombre' => 'Cerrado OK'],
            ['nombre' => 'Cerrado sin exito'],
        ]);
    }
}
