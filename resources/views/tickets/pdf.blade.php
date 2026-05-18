<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $ticket->id }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827;
            margin: 0;
            padding: 20px;
            background: #fff;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
        }

        .header {
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .header p {
            color: #4B5563;
            font-size: 12px;
            margin: 0;
        }

        .section {
            margin-bottom: 18px;
        }

        .label {
            color: #6B7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 6px;
            display: block;
        }

        .value {
            font-size: 14px;
            line-height: 1.55;
            color: #111827;
        }

        .box {
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            padding: 16px 18px;
            background: #F9FAFB;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field {
            margin-bottom: 12px;
        }

        .field strong {
            color: #111827;
        }

        .footer {
            margin-top: 28px;
            font-size: 11px;
            color: #6B7280;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-card {
            border-left: 4px solid #4F46E5;
            padding-left: 14px;
            margin-bottom: 18px;
        }

        @page {
            size: A4;
            margin: 18mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket #{{ $ticket->id }}</h1>
            <p>Documento generado el {{ $generatedAt->format('d/m/Y H:i') }}</p>
        </div>

        @php
            $repuestosCost = $ticket->repuestos->sum(fn ($repuesto) => $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario);
            $servicioCost = $ticket->monto_servicio ?? 0;
            $totalPagar = $ticket->total ?? ($repuestosCost + $servicioCost);
        @endphp

        <div class="section box ticket-card">
            <div class="field">
                <span class="label">Cliente</span>
                <div class="value">{{ $ticket->cliente?->nombre ?? 'N/A' }} (DNI: {{ $ticket->cliente?->dni ?? 'N/A' }})</div>
            </div>
            <div class="field">
                <span class="label">Equipo</span>
                <div class="value">{{ $ticket->equipo?->marca ?? 'N/A' }} {{ $ticket->equipo?->modelo ?? '' }}</div>
            </div>
            <div class="field">
                <span class="label">Descripción</span>
                <div class="value">{{ $ticket->descripcion ?: 'Sin descripción' }}</div>
            </div>
            <div class="field-grid">
                <div class="field">
                    <span class="label">Estado</span>
                    <div class="value">{{ $ticket->estadoActual?->nombre ?? 'Desconocido' }}</div>
                </div>
                <div class="field">
                    <span class="label">Técnico</span>
                    <div class="value">{{ $ticket->tecnico?->nombre ?? 'Sin asignar' }}</div>
                </div>
                <div class="field">
                    <span class="label">Repuesto</span>
                    <div class="value">${{ number_format($repuestosCost, 2, ',', '.') }}</div>
                </div>
                <div class="field">
                    <span class="label">Servicio</span>
                    <div class="value">${{ number_format($servicioCost, 2, ',', '.') }}</div>
                </div>
                <div class="field" style="grid-column: span 2;">
                    <span class="label">Total a pagar</span>
                    <div class="value"><strong>${{ number_format($totalPagar, 2, ',', '.') }}</strong></div>
                </div>
                <div class="field">
                    <span class="label">Fecha creación</span>
                    <div class="value">{{ $ticket->created_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <span>Ticket #{{ $ticket->id }}</span>
            <span>Generado por {{ auth()->user()?->name ?? 'Sistema' }}</span>
        </div>
    </div>
</body>
</html>
