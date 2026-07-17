@extends('layouts.app')

@section('title', 'Reporte de ventas')
@section('subtitle', 'Consulta ventas por día, semana, mes, mesero o estado de pago.')

@section('content')

<style>
    .report-card {
        padding: 0;
        overflow: hidden;
    }

    .report-header {
        padding: 26px;
        border-bottom: 1px solid #dde6ef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .report-header h2 {
        margin: 0;
        font-size: 24px;
    }

    .report-header p {
        margin: 6px 0 0;
        color: #6f7f91;
    }

    .filter-bar {
        padding: 24px 26px;
        border-bottom: 1px solid #dde6ef;
        display: flex;
        align-items: end;
        gap: 16px;
        flex-wrap: wrap;
    }

    .filter-item {
        min-width: 180px;
    }

    .filter-item.small {
        min-width: 130px;
    }

    .radio-group {
        display: flex;
        gap: 14px;
        align-items: center;
        font-weight: 800;
        padding-bottom: 14px;
    }

    .radio-group label {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .summary-grid {
        padding: 24px 26px;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
    }

    .summary-box {
        background: #f8fbff;
        border: 1px solid #dde6ef;
        border-radius: 16px;
        padding: 18px;
    }

    .summary-box span {
        color: #6f7f91;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .summary-box h2 {
        margin: 10px 0 0;
        color: #07182d;
        font-size: 25px;
    }

    .summary-box.fiado {
        background: #fff7e8;
        border-color: rgba(246,178,60,.55);
    }

    .summary-box.fiado h2 {
        color: #9a5500;
    }

    .private-message {
        margin: 24px 26px;
        background: #fff7e8;
        border: 1px solid rgba(246,178,60,.55);
        color: #8a5a00;
        border-radius: 16px;
        padding: 18px;
        font-weight: 700;
    }

    .table-wrap {
        padding: 0 26px 26px;
        overflow-x: auto;
    }

    .productos-list {
        max-width: 420px;
        line-height: 1.5;
    }

    .muted {
        color: #6f7f91;
    }

    .empty-report {
        padding: 40px;
        text-align: center;
        color: #6f7f91;
    }

    @media (max-width: 1200px) {
        .summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 800px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card report-card">
    <div class="report-header">
        <div>
            <h2>Reporte de ventas</h2>
            <p>{{ $periodoTexto }}</p>
        </div>

        @if($puedeVerTotales)
           <a href="{{ route('ventas.reporte.pdf', request()->only(['tipo', 'fecha', 'turno', 'trabajador_id', 'estado_pago', 'metodo_pago'])) }}" class="btn btn-primary">
    Descargar PDF
</a>
        @endif
    </div>

    <form method="GET" action="{{ route('ventas.reporte') }}" class="filter-bar">
        <div class="radio-group">
            <label>
                <input type="radio" name="tipo" value="dia" {{ $tipo == 'dia' ? 'checked' : '' }}>
                Día
            </label>

            <label>
                <input type="radio" name="tipo" value="semana" {{ $tipo == 'semana' ? 'checked' : '' }}>
                Semana
            </label>

            <label>
                <input type="radio" name="tipo" value="mes" {{ $tipo == 'mes' ? 'checked' : '' }}>
                Mes
            </label>
        </div>

        <div class="filter-item small">
            <label>Fecha</label>
            <input class="input" type="date" name="fecha" value="{{ $fecha }}">
        </div>
        <div class="filter-item small">
    <label>Turno</label>
    <select name="turno">
        <option value="">Todos</option>
        <option value="A" {{ ($turno ?? '') == 'A' ? 'selected' : '' }}>
            Turno A: 07:00 AM - 07:00 PM
        </option>
        <option value="B" {{ ($turno ?? '') == 'B' ? 'selected' : '' }}>
            Turno B: 07:15 PM - 06:55 AM
        </option>
    </select>
</div>

        <div class="filter-item">
            <label>Consumidor</label>
            <select name="trabajador_id">
                <option value="">Todos</option>
                @foreach($trabajadores as $trabajador)
                    <option value="{{ $trabajador->id }}" {{ $trabajadorId == $trabajador->id ? 'selected' : '' }}>
                        {{ $trabajador->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-item small">
            <label>Estado</label>
            <select name="estado_pago">
                <option value="">Todos</option>
                <option value="Pagado" {{ $estadoPago == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                <option value="Fiado" {{ $estadoPago == 'Fiado' ? 'selected' : '' }}>Fiado</option>
            </select>
        </div>

        <div class="filter-item small">
            <label>Método</label>
            <select name="metodo_pago">
                <option value="">Todos</option>
                <option value="Efectivo" {{ $metodoPago == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="QR" {{ $metodoPago == 'QR' ? 'selected' : '' }}>QR</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Aplicar</button>

        <a href="{{ route('ventas.reporte') }}" class="btn btn-light">
            Limpiar
        </a>
    </form>

    @if($puedeVerTotales)
        <div class="summary-grid">
            <div class="summary-box">
                <span>Total ventas</span>
                <h2>Bs {{ number_format($totalVentas, 2) }}</h2>
            </div>

            <div class="summary-box">
                <span>Efectivo</span>
                <h2>Bs {{ number_format($efectivo, 2) }}</h2>
            </div>

            <div class="summary-box">
                <span>QR</span>
                <h2>Bs {{ number_format($qr, 2) }}</h2>
            </div>

            <div class="summary-box fiado">
                <span>Fiado</span>
                <h2>Bs {{ number_format($fiado, 2) }}</h2>
            </div>

            <div class="summary-box">
                <span>Nro. ventas</span>
                <h2>{{ $cantidadVentas }}</h2>
            </div>

            <div class="summary-box">
                <span>Productos vendidos</span>
                <h2>{{ $productosVendidos }}</h2>
            </div>
        </div>
    @else
    <div class="private-message">
        Los totales generales del día están ocultos. 
        Puedes revisar las ventas registradas, productos consumidos y precios para verificar que el registro esté correcto.
    </div>
@endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Consumidor</th>
                    <th>Productos pedidos</th>
                    <th>Fecha y hora</th>
                    <th>Pago</th>
                    <th>Método</th>

                    <th>Total venta</th>
                    <th>Pendiente</th>

                    <th>Usuario</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventas as $venta)
                    <tr>
                        <td>#{{ $venta->id }}</td>

                        <td>
                            <strong>{{ $venta->trabajador->nombre ?? 'Sin mesero' }}</strong>
                        </td>

                        <td class="productos-list">
                            @foreach($venta->detalles as $detalle)
                                <div>
                                    {{ $detalle->cantidad }} x {{ $detalle->producto->nombre ?? 'Producto eliminado' }}

                                    @if($detalle->con_acompanamiento)
                                        <br>
                                        <small style="color:#9a5500;">
                                            + {{ $detalle->nombre_acompanamiento ?? 'Acompañamiento' }}

                                            @if($puedeVerTotales)
                                                (Bs {{ number_format($detalle->precio_acompanamiento, 2) }})
                                            @endif
                                        </small>
                                    @endif

                                    @if($puedeVerTotales)
                                        <td class="productos-list">
    @foreach($venta->detalles as $detalle)
        <div style="margin-bottom: 8px;">
            <strong>
                {{ $detalle->cantidad }} x {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
            </strong>

            <br>

            <span class="muted">
                Precio unitario: Bs {{ number_format($detalle->precio_unitario, 2) }}
            </span>

            @if($detalle->con_acompanamiento)
                <br>
                <small style="color:#9a5500;">
                    + {{ $detalle->nombre_acompanamiento ?? 'Acompañamiento' }}
                    (Bs {{ number_format($detalle->precio_acompanamiento, 2) }})
                </small>
            @endif

            <br>

            <span class="muted">
                Subtotal: Bs {{ number_format($detalle->subtotal, 2) }}
            </span>
        </div>
    @endforeach
</td>
                                    @endif
                                </div>
                            @endforeach
                        </td>

                        <td>
                            {{ $venta->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            @if($venta->estado_pago == 'Pagado')
                                <span class="badge ok">Pagado</span>
                            @else
                                <span class="badge warning">Fiado</span>
                            @endif
                        </td>

                        <td>{{ $venta->metodo_pago ?? '-' }}</td>

                      <td>
    <strong>Bs {{ number_format($venta->total, 2) }}</strong>
</td>

<td>
    @if($venta->saldo_pendiente > 0)
        <strong style="color:#9a5500;">
            Bs {{ number_format($venta->saldo_pendiente, 2) }}
        </strong>
    @else
        Bs 0.00
    @endif
</td>

                        <td>
                            {{ $venta->usuario->name ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
<td colspan="9">
                                <div class="empty-report">
                                No hay ventas registradas en este periodo.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection