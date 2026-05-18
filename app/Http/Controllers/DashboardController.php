<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Ticket;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $year = (int) $request->query('year', Carbon::now(config('app.timezone'))->year);

        $query = Ticket::with(['cliente', 'tecnico', 'estadoActual']);

        $this->applyTechnicianScope($query);

        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        if ($request->filled('dni')) {
            $query->whereHas('cliente', fn ($q) => $q->where('dni', 'like', '%'.$request->dni.'%'));
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_actual_id', $request->estado_id);
        }

        $tickets = $query->get();

        return view('dashboard.index', [
            'tickets' => $tickets,
            'estados' => Estado::all(),
            'year' => $year,
            'total' => $this->ticketsTable()->count(),
            'abiertos' => $this->ticketsTable()->where('estado_actual_id', 1)->count(),
            'proceso' => $this->ticketsTable()->where('estado_actual_id', 3)->count(),
            'cerrados' => $this->ticketsTable()->whereIn('estado_actual_id', [4, 5])->count(),
            'chartData' => $this->buildChartData($year),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildChartData(int $year): array
    {
        return [
            'porEstado' => $this->chartTicketsPorEstado(),
            'porDia' => $this->chartTicketsCreadosUltimosDias(30),
            'porTecnico' => $this->chartTicketsPorTecnico(),
            'recaudacionPorMes' => $this->recaudacionPorMes($year),
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

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private function recaudacionPorMes(int $year): array
    {
        $closedTickets = Ticket::with('repuestos')
            ->whereIn('estado_actual_id', [4, 5])
            ->whereYear('created_at', $year)
            ->get(['id', 'monto_servicio', 'created_at']);

        $months = collect(range(1, 12))->mapWithKeys(fn ($month) => [
            $month => ['servicio' => 0.0, 'repuestos' => 0.0],
        ])->toArray();

        foreach ($closedTickets as $ticket) {
            $month = (int) $ticket->created_at->format('n');
            $months[$month]['servicio'] += (float) $ticket->monto_servicio;
            $months[$month]['repuestos'] += $ticket->repuestos->sum(
                fn ($repuesto) => $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario
            );
        }

        $labels = [
            'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic',
        ];

        $values = collect($months)->map(fn ($data) => max(0.0, $data['servicio'] - $data['repuestos']))->values()->all();

        return [
            'labels' => $labels,
            'values' => $values,
            'year' => $year,
        ];
    }

    public function exportRecaudacion(Request $request): StreamedResponse
    {
        $year = (int) $request->query('year', Carbon::now(config('app.timezone'))->year);
        $report = $this->recaudacionPorMes($year);
        $filename = "recaudacion_{$year}.csv";

        return response()->streamDownload(function () use ($report) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Mes', 'Monto Servicio', 'Costo Repuestos', 'Recaudación Neta', 'Año']);

            foreach ($report['labels'] as $index => $label) {
                $monthNumber = $index + 1;
                $service = $this->recaudacionServicioPorMes($report['year'], $monthNumber);
                $repuestos = $this->recaudacionCostoRepuestosPorMes($report['year'], $monthNumber);
                $net = max(0.0, $service - $repuestos);

                fputcsv($handle, [
                    $label,
                    number_format($service, 2, '.', ''),
                    number_format($repuestos, 2, '.', ''),
                    number_format($net, 2, '.', ''),
                    $report['year'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function downloadDashboardPdf(Request $request): Response
    {
        $year = (int) $request->query('year', Carbon::now(config('app.timezone'))->year);

        $query = Ticket::with(['cliente', 'tecnico', 'estadoActual']);
        $this->applyTechnicianScope($query);

        if ($request->filled('ticket_id')) {
            $query->where('id', $request->ticket_id);
        }

        if ($request->filled('dni')) {
            $query->whereHas('cliente', fn ($q) => $q->where('dni', 'like', '%'.$request->dni.'%'));
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_actual_id', $request->estado_id);
        }

        $tickets = $query->get();
        $chartData = $this->buildChartData($year);

        $pdf = Pdf::loadView('dashboard.pdf', [
            'tickets' => $tickets,
            'total' => $this->ticketsTable()->count(),
            'abiertos' => $this->ticketsTable()->where('estado_actual_id', 1)->count(),
            'proceso' => $this->ticketsTable()->where('estado_actual_id', 3)->count(),
            'cerrados' => $this->ticketsTable()->whereIn('estado_actual_id', [4, 5])->count(),
            'chartData' => $chartData,
            'year' => $year,
            'generatedAt' => Carbon::now(),
        ]);

        return $pdf->download('dashboard_'.Carbon::now()->format('Y-m-d_H-i-s').'.pdf');
    }

    private function recaudacionServicioPorMes(int $year, int $month): float
    {
        return Ticket::whereIn('estado_actual_id', [4, 5])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('monto_servicio');
    }

    private function recaudacionCostoRepuestosPorMes(int $year, int $month): float
    {
        $tickets = Ticket::with('repuestos')
            ->whereIn('estado_actual_id', [4, 5])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return $tickets->sum(fn ($ticket) => $ticket->repuestos->sum(
            fn ($repuesto) => $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario
        ));
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
