<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Tickets - PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: white;
        }

        .container {
            padding: 20px;
            max-width: 100%;
        }

        .header {
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #6B7280;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #6B7280;
            margin-top: 5px;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .metric-card {
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            padding: 12px;
            background: #F9FAFB;
            text-align: center;
        }

        .metric-card h3 {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .metric-card .value {
            font-size: 28px;
            font-weight: 700;
            color: #1F2937;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #E5E7EB;
        }

        .tickets-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }

        .tickets-table thead {
            background: #F3F4F6;
            border: 1px solid #D1D5DB;
        }

        .tickets-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            color: #1F2937;
            border: 1px solid #D1D5DB;
        }

        .tickets-table td {
            padding: 8px;
            border: 1px solid #E5E7EB;
            vertical-align: top;
        }

        .tickets-table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 10px;
        }

        .status-1 {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-2 {
            background: #F3F4F6;
            color: #374151;
        }

        .status-3 {
            background: #DBEAFE;
            color: #0C4A6E;
        }

        .status-4 {
            background: #DCFCE7;
            color: #166534;
        }

        .status-5 {
            background: #FEE2E2;
            color: #7F1D1D;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #6B7280;
            font-size: 13px;
            border: 1px dashed #D1D5DB;
            border-radius: 6px;
            background: #F9FAFB;
        }

        .chart-info {
            font-size: 10px;
            color: #6B7280;
            margin-top: 5px;
        }

        .ticket-detail {
            margin-bottom: 15px;
            padding: 10px;
            border-left: 3px solid #4F46E5;
            background: #F9FAFB;
            page-break-inside: avoid;
        }

        .ticket-detail-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .ticket-detail-label {
            font-weight: 600;
            width: 120px;
            color: #4B5563;
        }

        .ticket-detail-value {
            flex: 1;
            color: #1F2937;
            word-break: break-word;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 15px;
            }

            .metrics {
                grid-template-columns: repeat(4, 1fr);
            }

            .section {
                page-break-inside: avoid;
            }
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Dashboard de Tickets</h1>
            <div class="meta-info">
                <span>Generado: {{ $generatedAt->format('d/m/Y H:i:s') }}</span>
                <span>Usuario: {{ auth()->user()->name }}</span>
            </div>
        </div>

        <div class="metrics">
            <div class="metric-card">
                <h3>Total Tickets</h3>
                <div class="value">{{ $total }}</div>
            </div>
            <div class="metric-card">
                <h3>Abiertos</h3>
                <div class="value">{{ $abiertos }}</div>
            </div>
            <div class="metric-card">
                <h3>En Proceso</h3>
                <div class="value">{{ $proceso }}</div>
            </div>
            <div class="metric-card">
                <h3>Cerrados</h3>
                <div class="value">{{ $cerrados }}</div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Tickets Listados</h2>
            
            @if ($tickets->isNotEmpty())
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th style="width: 8%">ID</th>
                            <th style="width: 15%">Cliente</th>
                            <th style="width: 12%">Equipo</th>
                            <th style="width: 12%">Estado</th>
                            <th style="width: 15%">Técnico</th>
                            <th style="width: 12%">Monto</th>
                            <th style="width: 15%">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ $ticket->cliente?->nombre ?? 'N/A' }}</td>
                                <td>{{ $ticket->equipo?->marca ?? 'N/A' }} {{ $ticket->equipo?->modelo ?? '' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $ticket->estadoActual?->id ?? 1 }}">
                                        {{ $ticket->estadoActual?->nombre ?? 'Desconocido' }}
                                    </span>
                                </td>
                                <td>{{ $ticket->tecnico?->nombre ?? 'Sin asignar' }}</td>
                                <td>${{ number_format($ticket->monto_servicio, 2) }}</td>
                                <td>{{ $ticket->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="chart-info">Total de registros mostrados: {{ $tickets->count() }}</p>
            @else
                <div class="no-data">
                    No hay tickets para mostrar con los filtros aplicados.
                </div>
            @endif
        </div>

        <div class="section">
            <h2 class="section-title">Detalles de Tickets</h2>
            
            @if ($tickets->isNotEmpty())
                @foreach ($tickets as $ticket)
                    <div class="ticket-detail">
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">ID Ticket:</span>
                            <span class="ticket-detail-value">#{{ $ticket->id }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Cliente:</span>
                            <span class="ticket-detail-value">{{ $ticket->cliente?->nombre ?? 'N/A' }} (DNI: {{ $ticket->cliente?->dni ?? 'N/A' }})</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Equipo:</span>
                            <span class="ticket-detail-value">{{ $ticket->equipo?->marca ?? 'N/A' }} {{ $ticket->equipo?->modelo ?? 'N/A' }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Descripción:</span>
                            <span class="ticket-detail-value">{{ $ticket->descripcion ?? 'Sin descripción' }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Estado:</span>
                            <span class="ticket-detail-value">{{ $ticket->estadoActual?->nombre ?? 'Desconocido' }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Técnico:</span>
                            <span class="ticket-detail-value">{{ $ticket->tecnico?->nombre ?? 'Sin asignar' }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Monto Servicio:</span>
                            <span class="ticket-detail-value">${{ number_format($ticket->monto_servicio, 2) }}</span>
                        </div>
                        <div class="ticket-detail-row">
                            <span class="ticket-detail-label">Fecha Creación:</span>
                            <span class="ticket-detail-value">{{ $ticket->created_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</span>
                        </div>
                        @if ($ticket->repuestos->isNotEmpty())
                            <div class="ticket-detail-row">
                                <span class="ticket-detail-label">Repuestos:</span>
                                <span class="ticket-detail-value">
                                    @foreach ($ticket->repuestos as $repuesto)
                                        <br>- {{ $repuesto->nombre }}: {{ $repuesto->pivot->cantidad }} x ${{ number_format($repuesto->pivot->precio_unitario, 2) }}
                                    @endforeach
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="no-data">
                    No hay detalles de tickets para mostrar.
                </div>
            @endif
        </div>

        <div class="section">
            <h2 class="section-title">Resumen Estadístico</h2>
            <div class="ticket-detail">
                <div class="ticket-detail-row">
                    <span class="ticket-detail-label">Total Tickets:</span>
                    <span class="ticket-detail-value">{{ $total }}</span>
                </div>
                <div class="ticket-detail-row">
                    <span class="ticket-detail-label">Abiertos:</span>
                    <span class="ticket-detail-value">{{ $abiertos }} ({{ $total > 0 ? round(($abiertos / $total) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="ticket-detail-row">
                    <span class="ticket-detail-label">En Proceso:</span>
                    <span class="ticket-detail-value">{{ $proceso }} ({{ $total > 0 ? round(($proceso / $total) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="ticket-detail-row">
                    <span class="ticket-detail-label">Cerrados:</span>
                    <span class="ticket-detail-value">{{ $cerrados }} ({{ $total > 0 ? round(($cerrados / $total) * 100, 1) : 0 }}%)</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
