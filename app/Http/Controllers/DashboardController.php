<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estado;
use App\Models\Ticket;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const ESTADO_CHART_COLORS = [
        1 => '#f59e0b',
        2 => '#64748b',
        3 => '#0ea5e9',
        4 => '#10b981',
        5 => '#e11d48',
    ];

    public function index(Request $request)
    {
        $query = Ticket::with(['cliente', 'tecnico', 'estadoActual']);

        $this->applyTechnicianScope($query);

        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_actual_id', $request->estado_id);
        }

        $tickets = $query->get();

        return view('dashboard.index', [
            'tickets' => $tickets,
            'clientes' => Cliente::all(),
            'estados' => Estado::all(),
            'total' => $this->ticketsTable()->count(),
            'abiertos' => $this->ticketsTable()->where('estado_actual_id', 1)->count(),
            'proceso' => $this->ticketsTable()->where('estado_actual_id', 3)->count(),
            'cerrados' => $this->ticketsTable()->whereIn('estado_actual_id', [4, 5])->count(),
            'chartData' => $this->buildChartData(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildChartData(): array
    {
        return [
            'porEstado' => $this->chartTicketsPorEstado(),
            'porDia' => $this->chartTicketsCreadosUltimosDias(30),
            'porTecnico' => $this->chartTicketsPorTecnico(),
        ];
    }

    /**
     * @return array<int, array{label: string, count: int, color: string}>
     */
    private function chartTicketsPorEstado(): array
    {
        $counts = $this->ticketsTable()
            ->selectRaw('estado_actual_id, COUNT(*) as cnt')
            ->groupBy('estado_actual_id')
            ->pluck('cnt', 'estado_actual_id')
            ->map(fn ($c) => (int) $c);

        $result = Estado::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Estado $e) => [
                'label' => $e->nombre,
                'count' => (int) $counts->get($e->id, 0),
                'color' => self::ESTADO_CHART_COLORS[$e->id] ?? '#94a3b8',
            ])
            ->all();

        if ($result === []) {
            return [
                ['label' => 'Sin datos', 'count' => 0, 'color' => '#94a3b8'],
            ];
        }

        return $result;
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    private function chartTicketsCreadosUltimosDias(int $days): array
    {
        $tz = config('app.timezone');
        $start = Carbon::now($tz)->subDays($days - 1)->startOfDay();

        $ticketsQuery = Ticket::query()->where('created_at', '>=', $start);
        $this->applyTechnicianScope($ticketsQuery);

        $tickets = $ticketsQuery->get(['created_at']);

        $countsByDay = $tickets->groupBy(
            fn (Ticket $t) => $t->created_at->timezone($tz)->format('Y-m-d')
        )->map(fn ($g) => $g->count());

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');
            $values[] = (int) $countsByDay->get($key, 0);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @return array<int, array{label: string, count: int}>
     */
    private function chartTicketsPorTecnico(): array
    {
        $rows = $this->ticketsTable()
            ->selectRaw('tecnico_id, COUNT(*) as cnt')
            ->groupBy('tecnico_id')
            ->orderByDesc('cnt')
            ->get();

        if ($rows->isEmpty()) {
            return [
                ['label' => 'Sin datos', 'count' => 0],
            ];
        }

        $ids = $rows->pluck('tecnico_id')->filter()->unique()->values();
        $tecnicos = Usuario::query()->whereIn('id', $ids)->get()->keyBy('id');

        return $rows->map(function ($row) use ($tecnicos) {
            $id = $row->tecnico_id;
            $label = $id === null
                ? 'Sin asignar'
                : (string) ($tecnicos->get($id)?->nombre ?? 'Técnico #'.$id);

            return [
                'label' => $label,
                'count' => (int) $row->cnt,
            ];
        })->all();
    }

    private function ticketsTable(): QueryBuilder
    {
        $q = DB::table('tickets');
        $user = auth()->user();

        if ($user?->isTecnico()) {
            $uid = $user->usuario?->id;
            if ($uid) {
                $q->where('tecnico_id', $uid);
            } else {
                $q->whereRaw('0 = 1');
            }
        }

        return $q;
    }

    private function applyTechnicianScope(Builder $query): void
    {
        $user = auth()->user();

        if (! $user?->isTecnico()) {
            return;
        }

        $uid = $user->usuario?->id;

        if ($uid) {
            $query->where('tecnico_id', $uid);
        } else {
            $query->whereRaw('0 = 1');
        }
    }
}
