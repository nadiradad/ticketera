<?php

use App\Models\Estado;
use App\Models\User;

test('authenticated users can view the dashboard with chart data', function () {
    Estado::query()->create(['nombre' => 'Abierto']);
    Estado::query()->create(['nombre' => 'En proceso']);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard', absolute: false))
        ->assertOk()
        ->assertSee('chart-tickets-por-estado', false)
        ->assertSee('chart-tickets-por-dia', false)
        ->assertSee('chart-tickets-por-tecnico', false)
        ->assertSee('dashboard-charts-payload', false)
        ->assertSee('porEstado', false);
});
