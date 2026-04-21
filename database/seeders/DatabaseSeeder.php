<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\StaffProfileService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'test@example.com',
            'rol' => 'administrador',
        ]);

        User::factory()->create([
            'name' => 'Técnico principal',
            'email' => 'tecnico@example.com',
            'rol' => 'tecnico',
        ]);

        User::factory()->create([
            'name' => 'Recepción',
            'email' => 'recepcionista@example.com',
            'rol' => 'recepcionista',
        ]);

        $this->call([
            EstadosSeeder::class,
            DatosInicialesSeeder::class,
        ]);

        foreach (User::query()->get() as $user) {
            app(StaffProfileService::class)->syncUsuarioProfile($user);
        }

        $this->call([
            TicketsDemoSeeder::class,
        ]);
    }
}
