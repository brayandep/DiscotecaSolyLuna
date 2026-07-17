<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #07182d;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #f6b23c;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #061527;
        }

        .subtitle {
            color: #555;
            margin-top: 4px;
            font-size: 11px;
        }

        .periodo {
            margin-top: 6px;
            color: #333;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .summary td {
            border: 1px solid #dde6ef;
            padding: 7px;
            background: #f8fbff;
            width: 16.66%;
        }

        .label {
            color: #666;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table th {
            background: #061527;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 8px;
            border: 1px solid #061527;
        }

        .table td {
            border: 1px solid #dde6ef;
            padding: 6px;
            vertical-align: top;
            font-size: 8.5px;
            word-wrap: break-word;
        }

        .col-nro {
            width: 5%;
        }

        .col-mesero {
            width: 12%;
        }

        .col-productos {
            width: 31%;
        }

        .col-fecha {
            width: 12%;
        }

        .col-pago {
            width: 8%;
        }

        .col-metodo {
            width: 8%;
        }

        .col-total {
            width: 8%;
        }

        .col-pendiente {
            width: 8%;
        }

        .col-usuario {
            width: 8%;
        }

        .producto-item {
            margin-bottom: 4px;
            line-height: 1.35;
        }

        .muted {
            color: #666;
        }

        .pagado {
            color: #157347;
            font-weight: bold;
        }

        .fiado {
            color: #9a5500;
            font-weight: bold;
        }

        .footer {
            margin-top: 14px;
            font-size: 8px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="brand">SOL & LUNA - Reporte de ventas</div>

    <div class="subtitle">
        Reporte generado por el sistema administrativo
    </div>

    <div class="periodo">
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
    </div>
</div>

<table class="summary">
    <tr>
        <td>
            <div class="label">Total ventas</div>
            <div class="value">Bs {{ number_format($totalVentas, 2) }}</div>
        </td>

        <td>
            <div class="label">Efectivo</div>
            <div class="value">Bs {{ number_format($efectivo, 2) }}</div>
        </td>

        <td>
            <div class="label">QR</div>
            <div class="value">Bs {{ number_format($qr, 2) }}</div>
        </td>

        <td>
            <div class="label">Fiado</div>
            <div class="value">Bs {{ number_format($fiado, 2) }}</div>
        </td>

        <td>
            <div class="label">Nro. ventas</div>
            <div class="value">{{ $cantidadVentas }}</div>
        </td>

        <td>
            <div class="label">Productos vendidos</div>
            <div class="value">{{ $productosVendidos }}</div>
        </td>
    </tr>
</table>

<table class="table">
    <thead>
        <tr>
            <th class="col-nro">Nro</th>
            <th class="col-mesero">Mesero</th>
            <th class="col-productos">Productos pedidos</th>
            <th class="col-fecha">Fecha y hora</th>
            <th class="col-pago">Pago</th>
            <th class="col-metodo">Método</th>
            <th class="col-total">Total</th>
            <th class="col-pendiente">Pendiente</th>
            <th class="col-usuario">Usuario</th>
        </tr>
    </thead>

    <tbody>
        @forelse($ventas as $venta)
            <tr>
                <td>#{{ $venta->id }}</td>

                <td>
                    {{ $venta->trabajador->nombre ?? 'Sin mesero' }}
                </td>

                <td>
                    @foreach($venta->detalles as $detalle)
                        <div class="producto-item">
                            <strong>
                                {{ $detalle->cantidad }} x {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                            </strong>

                            <br>

                            <span class="muted">
                                Unitario: Bs {{ number_format($detalle->precio_unitario, 2) }}
                            </span>

                            @if($detalle->con_acompanamiento)
                                <br>
                                <span class="fiado">
                                    + {{ $detalle->nombre_acompanamiento ?? 'Acompañamiento' }}
                                    (Bs {{ number_format($detalle->precio_acompanamiento, 2) }})
                                </span>
                            @endif

                            <br>

                            <span class="muted">
                                Subtotal: Bs {{ number_format($detalle->subtotal, 2) }}
                            </span>
                        </div>
                    @endforeach
                </td>

                <td>
                    {{ $venta->created_at->format('d/m/Y H:i') }}
                </td>

                <td>
                    @if($venta->estado_pago == 'Pagado')
                        <span class="pagado">Pagado</span>
                    @else
                        <span class="fiado">Fiado</span>
                    @endif
                </td>

                <td>
                    {{ $venta->metodo_pago ?? '-' }}
                </td>

                <td>
                    Bs {{ number_format($venta->total, 2) }}
                </td>

                <td>
                    Bs {{ number_format($venta->saldo_pendiente, 2) }}
                </td>

                <td>
                    {{ $venta->usuario->name ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:20px;">
                    No hay ventas registradas en este periodo.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Generado el {{ now()->format('d/m/Y H:i') }} - SOL & LUNA
</div>

</body>
</html>