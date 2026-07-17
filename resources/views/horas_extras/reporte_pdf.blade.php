<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de horas extras</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #07182d;
        }

        .header {
            border-bottom: 2px solid #f6b23c;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #061527;
        }

        .subtitle {
            color: #555;
            margin-top: 4px;
        }

        .summary {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #dde6ef;
            padding: 8px;
            background: #f8fbff;
        }

        .label {
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .value {
            font-size: 15px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #061527;
            color: white;
            padding: 7px;
            text-align: left;
            font-size: 10px;
        }

        td {
            border-bottom: 1px solid #dde6ef;
            padding: 7px;
            vertical-align: top;
        }

        .fiado {
            color: #9a5500;
            font-weight: bold;
        }

        .pagado {
            color: #157347;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="brand">SOL & LUNA - Reporte de horas extras</div>
<div class="subtitle">
    {{ $periodoTexto }}

    @if(!empty($turno))
        |
        @if($turno == 'A')
            Turno A: 07:00 AM - 07:00 PM
        @elseif($turno == 'B')
            Turno B: 07:15 PM - 06:55 AM
        @endif
    @else
        | Todos los turnos
    @endif
</div></div>

<table class="summary">
    <tr>
        <td>
            <div class="label">Total pagado</div>
            <div class="value">Bs {{ number_format($totalPagado, 2) }}</div>
        </td>
        <td>
            <div class="label">Efectivo</div>
            <div class="value">Bs {{ number_format($totalEfectivo, 2) }}</div>
        </td>
        <td>
            <div class="label">QR</div>
            <div class="value">Bs {{ number_format($totalQr, 2) }}</div>
        </td>
        <td>
            <div class="label">Fiado</div>
            <div class="value">Bs {{ number_format($totalFiado, 2) }}</div>
        </td>
        <td>
            <div class="label">Registros</div>
            <div class="value">{{ $cantidadRegistros }}</div>
        </td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Trabajador</th>
            <th>Fecha</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Horas</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Pago</th>
            <th>Método</th>
            <th>Usuario</th>
        </tr>
    </thead>

    <tbody>
        @forelse($horasExtras as $hora)
            <tr>
                <td>{{ $hora->trabajador->nombre ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($hora->fecha)->format('d/m/Y') }}</td>
                <td>{{ $hora->hora_entrada }}</td>
                <td>{{ $hora->hora_salida }}</td>
                <td>{{ number_format($hora->horas_calculadas, 2) }}</td>
                <td>{{ $hora->tipo_tarifa ?? '-' }}</td>

                <td>
                    @if($hora->estado_pago == 'Fiado')
                        Bs {{ number_format($hora->saldo_pendiente, 2) }}
                    @else
                        Bs {{ number_format($hora->monto_pagado, 2) }}
                    @endif
                </td>

                <td>
                    @if($hora->estado_pago == 'Pagado')
                        <span class="pagado">Pagado</span>
                    @else
                        <span class="fiado">Fiado</span>
                    @endif
                </td>

                <td>{{ $hora->metodo_pago ?? '-' }}</td>
                <td>{{ $hora->usuario->name ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align:center;">
                    No hay horas extras registradas en este periodo.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>