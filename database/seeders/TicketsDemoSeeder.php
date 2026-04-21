<?php

namespace Database\Seeders;

use App\Models\EstadoHistorial;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TicketsDemoSeeder extends Seeder
{
    /**
     * Sufijo en descripción para poder re-ejecutar el seeder sin duplicar datos.
     */
    private const DEMO_TAIL = "\n\n(dato de demostración — podés borrarlo)";

    public function run(): void
    {
        $user = User::query()->where('email', 'test@example.com')->first()
            ?? User::query()->first();

        if ($user === null) {
            $this->command?->warn('No hay usuarios en la base. Ejecutá primero DatabaseSeeder.');

            return;
        }

        $this->purgeDemoTickets();

        $rows = [
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 1, 'd' => 'La notebook no enciende: led de carga no se enciende. Probé otro cargador.', 'm' => 8500.00, 'days' => 1],
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 1, 'd' => 'Pantalla con parpadeos al abrir el panel más de 120 Hz.', 'm' => 0.00, 'days' => 2],
            ['c' => 1, 'e' => 1, 't' => null, 'est' => 1, 'd' => 'Solicitud de ampliación de RAM y limpieza interna.', 'm' => 12000.00, 'days' => 3],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 2, 'd' => 'PC de escritorio: arranque lento tras actualización de Windows.', 'm' => 6500.00, 'days' => 4],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 2, 'd' => 'Ruido de ventilador constante incluso en reposo.', 'm' => 4000.00, 'days' => 5],
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 3, 'd' => 'Reemplazo de disco HDD por SSD ya diagnosticado; pendiente de clonado.', 'm' => 15000.00, 'days' => 6],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 3, 'd' => 'Instalación de drivers de video y prueba de estrés.', 'm' => 7200.00, 'days' => 7],
            ['c' => 1, 'e' => 1, 't' => null, 'est' => 3, 'd' => 'Teclado con teclas F1–F4 sin respuesta tras derrame de líquido (ya secado).', 'm' => 9800.00, 'days' => 8],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 3, 'd' => 'Configuración de red cableada e impresora compartida en oficina.', 'm' => 5500.00, 'days' => 9],
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 4, 'd' => 'Cambio de pasta térmica y limpieza: temperaturas normales verificadas.', 'm' => 11000.00, 'days' => 10],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 4, 'd' => 'Reinstalación limpia del SO y backup de datos del usuario completado.', 'm' => 13500.00, 'days' => 11],
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 4, 'd' => 'Batería reemplazada; autonomía verificada en 2 ciclos de carga.', 'm' => 22000.00, 'days' => 12],
            ['c' => 2, 'e' => 2, 't' => 1, 'est' => 5, 'd' => 'Placa madre con daño en socket: no conviene reparación económica.', 'm' => 3000.00, 'days' => 13],
            ['c' => 1, 'e' => 1, 't' => 1, 'est' => 5, 'd' => 'Disco con sectores defectuosos irreversibles; usuario optó por no continuar.', 'm' => 2500.00, 'days' => 14],
            ['c' => 2, 'e' => 2, 't' => null, 'est' => 4, 'd' => 'Actualización de BIOS y chequeo de estabilidad: sin reinicios en 48 h.', 'm' => 8900.00, 'days' => 15],
        ];

        $created = [];

        foreach ($rows as $row) {
            $payload = [
                'cliente_id' => $row['c'],
                'equipo_id' => $row['e'],
                'tecnico_id' => $row['t'],
                'estado_actual_id' => $row['est'],
                'descripcion' => $row['d'].self::DEMO_TAIL,
                'monto_servicio' => $row['m'],
                'total' => $row['m'],
            ];

            if (Schema::hasColumn('tickets', 'user_id')) {
                $payload['user_id'] = $user->id;
            }

            /** @var Ticket $ticket */
            $ticket = Ticket::query()->create($payload);

            $createdAt = Carbon::now()->subDays($row['days'])->setTime(rand(9, 18), rand(0, 59), 0);
            $ticket->timestamps = false;
            $ticket->created_at = $createdAt;
            $ticket->updated_at = $createdAt;
            $ticket->save();
            $ticket->timestamps = true;

            $created[] = ['ticket' => $ticket, 'estado' => (int) $row['est'], 'at' => $createdAt];
        }

        $this->seedHistorial($created);
        $this->seedRepuestos($created);
    }

    private function purgeDemoTickets(): void
    {
        $query = Ticket::query()->where('descripcion', 'like', '%dato de demostración%');

        foreach ($query->get() as $ticket) {
            $ticket->repuestos()->detach();
            EstadoHistorial::query()->where('ticket_id', $ticket->id)->delete();
            $ticket->delete();
        }
    }

    /**
     * @param  array<int, array{ticket: Ticket, estado: int, at: Carbon}>  $created
     */
    private function seedHistorial(array $created): void
    {
        foreach ($created as $item) {
            $ticket = $item['ticket'];
            $estado = $item['estado'];
            $base = $item['at']->copy();

            if (in_array($estado, [4, 5], true)) {
                $this->addHistorialRow($ticket->id, 1, $base->copy()->subHours(8), 'Recepción del equipo y registro en sistema.');
                $this->addHistorialRow($ticket->id, 3, $base->copy()->subHours(3), 'Diagnóstico y trabajo en curso.');
                $this->addHistorialRow($ticket->id, $estado, $base, $estado === 4 ? 'Cierre conforme al cliente.' : 'Cierre sin reparación posible / no aprobada.');
            } elseif ($estado === 3) {
                $this->addHistorialRow($ticket->id, 1, $base->copy()->subHours(6), 'Ticket ingresado.');
                $this->addHistorialRow($ticket->id, 2, $base->copy()->subHours(4), 'Asignado a técnico.');
                $this->addHistorialRow($ticket->id, 3, $base, 'En taller / en proceso.');
            } elseif ($estado === 2) {
                $this->addHistorialRow($ticket->id, 1, $base->copy()->subHours(5), 'Ingreso y checklist inicial.');
                $this->addHistorialRow($ticket->id, 2, $base, 'Derivado a técnico asignado.');
            } else {
                $this->addHistorialRow($ticket->id, 1, $base, 'Ticket abierto a la espera de diagnóstico.');
            }
        }
    }

    private function addHistorialRow(int $ticketId, int $estadoId, Carbon $fecha, string $comentario): void
    {
        EstadoHistorial::query()->create([
            'ticket_id' => $ticketId,
            'estado_id' => $estadoId,
            'usuario_id' => 1,
            'comentario' => $comentario,
            'fecha' => $fecha,
        ]);
    }

    /**
     * @param  array<int, array{ticket: Ticket, estado: int, at: Carbon}>  $created
     */
    private function seedRepuestos(array $created): void
    {
        $attachIndices = [5, 6, 9, 10, 11, 14];

        foreach ($attachIndices as $i) {
            if (! isset($created[$i - 1])) {
                continue;
            }

            $ticket = $created[$i - 1]['ticket'];

            if ($i % 2 === 1) {
                $ticket->repuestos()->attach(1, ['cantidad' => 1, 'precio_unitario' => 52.00]);
            } else {
                $ticket->repuestos()->attach(2, ['cantidad' => 2, 'precio_unitario' => 31.50]);
            }

            $ticket->refresh();

            $totalRepuestos = $ticket->repuestos->sum(
                fn ($r) => $r->pivot->cantidad * $r->pivot->precio_unitario,
            );

            $ticket->total = (float) $ticket->monto_servicio + $totalRepuestos;
            $ticket->save();
        }
    }
}
