@extends('layouts.app')

@section('title', 'Horas extras')
@section('subtitle', 'Registra pagos por horas extras y revisa el reporte por trabajador.')

@section('content')

<style>
    .extra-layout {
        display: grid;
        grid-template-columns: .9fr 1.3fr;
        gap: 24px;
        align-items: start;
    }

    .panel-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #dde6ef;
        box-shadow: 0 18px 45px rgba(5,18,32,.08);
        overflow: hidden;
    }

    .panel-header {
        padding: 24px 26px;
        border-bottom: 1px solid #dde6ef;
    }

    .panel-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .panel-header p {
        margin: 6px 0 0;
        color: #6f7f91;
    }

    .panel-body {
        padding: 24px 26px;
    }

    .quick-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin: 14px 0 20px;
    }

    .quick-btn {
        border: 1px solid rgba(246,178,60,.45);
        background: #fff7e8;
        color: #8a5a00;
        border-radius: 14px;
        padding: 14px;
        cursor: pointer;
        font-weight: 900;
        text-align: center;
    }

    .quick-btn:hover {
        background: #f6b23c;
        color: #061527;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    .summary-box {
        background: white;
        border: 1px solid #dde6ef;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 12px 30px rgba(5,18,32,.06);
    }

    .summary-box span {
        color: #6f7f91;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .summary-box h2 {
        margin: 10px 0 0;
        font-size: 24px;
    }

    .summary-box.fiado {
        background: #fff7e8;
        border-color: rgba(246,178,60,.55);
    }

    .filter-bar {
        display: flex;
        align-items: end;
        gap: 12px;
        flex-wrap: wrap;
        padding: 18px;
        border-bottom: 1px solid #dde6ef;
    }

    .filter-item {
        min-width: 160px;
    }

    .radio-group {
        display: flex;
        gap: 12px;
        padding-bottom: 14px;
        font-weight: 800;
    }

    .radio-group label {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .private-message {
        margin-bottom: 20px;
        background: #fff7e8;
        border: 1px solid rgba(246,178,60,.55);
        color: #8a5a00;
        border-radius: 16px;
        padding: 16px;
        font-weight: 700;
    }

    @media (max-width: 1100px) {
        .extra-layout,
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .form-row,
        .quick-buttons {
            grid-template-columns: 1fr;
        }
    }
</style>

@if($puedeVerTotales)
    <div class="summary-grid">
        <div class="summary-box">
            <span>Total pagado</span>
            <h2>Bs {{ number_format($totalPagado, 2) }}</h2>
        </div>

        <div class="summary-box">
            <span>Efectivo</span>
            <h2>Bs {{ number_format($totalEfectivo, 2) }}</h2>
        </div>

        <div class="summary-box">
            <span>QR</span>
            <h2>Bs {{ number_format($totalQr, 2) }}</h2>
        </div>

        <div class="summary-box fiado">
            <span>Fiado</span>
            <h2>Bs {{ number_format($totalFiado, 2) }}</h2>
        </div>

        <div class="summary-box">
            <span>Registros</span>
            <h2>{{ $cantidadRegistros }}</h2>
        </div>
    </div>
@else
    <div class="private-message">
        Los totales generales de horas extras solo están disponibles para la dueña o SuperAdmin.
    </div>
@endif

<div class="extra-layout">
    <div class="panel-card">
        <div class="panel-header">
            <h2>Registrar pieza</h2>
            <p>Selecciona una señorita, horario y forma de pago.</p>
        </div>

        <div class="panel-body">
            <form action="{{ route('horas-extras.store') }}" method="POST">
    @csrf

    <label>Señorita <span class="required">*</span></label>
    <select name="trabajador_id" required>
        <option value="">Selecciona una señorita</option>
        @foreach($trabajadores as $trabajador)
            <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
        @endforeach
    </select>

    <br><br>

    <label>Selecciona hora</label>
    <div class="quick-buttons">
        <button type="button" class="quick-btn" onclick="setTarifa(0.5, 150, 'Media hora')">
            ½ hora<br>Bs 150
        </button>

        <button type="button" class="quick-btn" onclick="setTarifa(1, 300, '1 hora')">
            1 hora<br>Bs 300
        </button>

        <button type="button" class="quick-btn" onclick="setTarifa(2, 600, '2 horas')">
            2 horas<br>Bs 600
        </button>
    </div>

    <div class="form-row">
        <div>
            <label>Hora entrada <span class="required">*</span></label>
            <input class="input" type="time" name="hora_entrada" id="hora_entrada" value="{{ date('H:i') }}" required>
        </div>

        <div>
            <label>Hora salida <span class="required">*</span></label>
            <input class="input" type="time" name="hora_salida" id="hora_salida" required>
        </div>
    </div>

    <div>
        <label>Monto a pagar o fiar <span class="required">*</span></label>
        <input class="input" type="number" step="0.01" name="monto_pagado" id="monto_pagado" value="300" min="0" required>
    </div>

    <input type="hidden" name="tipo_tarifa" id="tipo_tarifa" value="1 hora">

    <div class="form-row">
        <div>
            <label>Estado de pago <span class="required">*</span></label>
            <select name="estado_pago" id="estado_pago" required onchange="cambiarEstadoPagoExtra()">
                <option value="Pagado">Pagado</option>
                <option value="Fiado">Fiado</option>
            </select>
        </div>

        <div id="metodoPagoExtraBox">
            <label>Método de pago</label>
            <select name="metodo_pago" id="metodo_pago">
                <option value="Efectivo">Efectivo</option>
                <option value="QR">QR</option>
            </select>
        </div>
    </div>

    <br>

    <label>Observación</label>
    <textarea name="observacion" rows="3" placeholder="Ejemplo: apoyo extra en barra, evento lleno, ingreso de emergencia, etc."></textarea>

    <br><br>

    <button type="submit" class="btn btn-primary">
        Registrar hora extra
    </button>
</form>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h2>Reporte de horas extras</h2>
        <p>Filtra por fecha, trabajador, estado o método de pago.</p>
    </div>

    @if($puedeVerTotales)
        <a href="{{ route('horas-extras.pdf', request()->only(['tipo', 'fecha', 'turno', 'trabajador_id', 'estado_pago', 'metodo_pago'])) }}" class="btn btn-primary">
    Descargar PDF
</a>
    @endif
</div>

        <form method="GET" action="{{ route('horas-extras.index') }}" class="filter-bar">
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

            <div class="filter-item">
                <label>Fecha</label>
                <input class="input" type="date" name="fecha" value="{{ $fecha }}">
            </div>
            <div class="filter-item">
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
                <label>Trabajador</label>
                <select name="trabajador_id">
                    <option value="">Todos</option>
                    @foreach($trabajadores as $trabajador)
                        <option value="{{ $trabajador->id }}" {{ $trabajadorId == $trabajador->id ? 'selected' : '' }}>
                            {{ $trabajador->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Estado</label>
                <select name="estado_pago">
                    <option value="">Todos</option>
                    <option value="Pagado" {{ $estadoPago == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="Fiado" {{ $estadoPago == 'Fiado' ? 'selected' : '' }}>Fiado</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Método</label>
                <select name="metodo_pago">
                    <option value="">Todos</option>
                    <option value="Efectivo" {{ $metodoPago == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="QR" {{ $metodoPago == 'QR' ? 'selected' : '' }}>QR</option>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Aplicar</button>
            <a class="btn btn-light" href="{{ route('horas-extras.index') }}">Limpiar</a>
        </form>

        <div style="padding: 0 18px 18px; overflow-x:auto;">
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
                    </tr>
                </thead>

                <tbody>
                    @forelse($horasExtras as $hora)
                        <tr>
                            <td><strong>{{ $hora->trabajador->nombre ?? '-' }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($hora->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $hora->hora_entrada }}</td>
                            <td>{{ $hora->hora_salida }}</td>
                            <td>{{ number_format($hora->horas_calculadas, 2) }}</td>
                            <td>{{ $hora->tipo_tarifa ?? '-' }}</td>

                            <td>
                                @if($hora->estado_pago == 'Fiado')
                                    <strong style="color:#9a5500;">Bs {{ number_format($hora->saldo_pendiente, 2) }}</strong>
                                @else
                                    <strong>Bs {{ number_format($hora->monto_pagado, 2) }}</strong>
                                @endif
                            </td>

                            <td>
                                @if($hora->estado_pago == 'Pagado')
                                    <span class="badge ok">Pagado</span>
                                @else
                                    <span class="badge warning">Fiado</span>
                                @endif
                            </td>

                            <td>{{ $hora->metodo_pago ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; color:#6f7f91; padding:30px;">
                                No hay horas extras registradas en este periodo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <br>
            {{ $horasExtras->links() }}
        </div>
    </div>
</div>
<script>
    function setTarifa(horas, monto, tipo) {
        document.getElementById('monto_pagado').value = monto;
        document.getElementById('tipo_tarifa').value = tipo;

        const entradaInput = document.getElementById('hora_entrada');
        const salidaInput = document.getElementById('hora_salida');

        if (!entradaInput.value) {
            return;
        }

        const partes = entradaInput.value.split(':');
        const fecha = new Date();

        fecha.setHours(parseInt(partes[0]));
        fecha.setMinutes(parseInt(partes[1]));
        fecha.setSeconds(0);

        fecha.setMinutes(fecha.getMinutes() + (horas * 60));

        const hh = String(fecha.getHours()).padStart(2, '0');
        const mm = String(fecha.getMinutes()).padStart(2, '0');

        salidaInput.value = hh + ':' + mm;
    }

    function cambiarEstadoPagoExtra() {
        const estado = document.getElementById('estado_pago').value;
        const box = document.getElementById('metodoPagoExtraBox');
        const metodo = document.getElementById('metodo_pago');

        if (estado === 'Fiado') {
            box.style.display = 'none';
            metodo.value = '';
        } else {
            box.style.display = 'block';
            metodo.value = 'Efectivo';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        setTarifa(1, 300, '1 hora');
        cambiarEstadoPagoExtra();
    });
</script>

@endsection