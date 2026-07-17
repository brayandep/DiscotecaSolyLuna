@extends('layouts.app')

@section('title', 'Trabajadores')
@section('subtitle', 'Registra y visualiza a los meseros o trabajadores disponibles.')

@section('actions')
    <button type="button" class="btn btn-primary" onclick="toggleRegistroTrabajador()">
        ＋ Registrar trabajador rápido
    </button>
@endsection

@section('content')

<style>
    .workers-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 22px;
        border: 1px solid #dde6ef;
        box-shadow: 0 12px 30px rgba(5, 18, 32, .06);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        right: -35px;
        top: -35px;
        width: 100px;
        height: 100px;
        background: rgba(246,178,60,.14);
        border-radius: 50%;
    }

    .stat-label {
        color: #6f7f91;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stat-number {
        color: #07182d;
        font-size: 34px;
        font-weight: 900;
    }

    .quick-register {
        display: none;
        margin-bottom: 22px;
    }

    .quick-register.active {
        display: block;
    }

    .quick-card {
        background:
            linear-gradient(135deg, rgba(246,178,60,.10), rgba(255,255,255,1) 45%),
            white;
        border: 1px solid rgba(246,178,60,.35);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 18px 45px rgba(5,18,32,.08);
    }

    .quick-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .quick-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        background: linear-gradient(135deg, #061527, #0e2d4d);
        color: #f6b23c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        border: 1px solid rgba(246,178,60,.55);
    }

    .quick-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .quick-header p {
        margin: 5px 0 0;
        color: #6f7f91;
    }

    .quick-form {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .table-card {
        overflow: hidden;
        padding: 0;
    }

    .table-header {
        padding: 24px 26px;
        border-bottom: 1px solid #dde6ef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .table-header p {
        margin: 5px 0 0;
        color: #6f7f91;
    }

    .worker-name {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
    }

    .worker-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: linear-gradient(135deg, #061527, #0e2d4d);
        color: #f6b23c;
        border: 1px solid rgba(246,178,60,.45);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
    }

    .empty-state {
        text-align: center;
        padding: 45px;
        color: #6f7f91;
    }

    .action-form {
        display: inline;
    }

    @media (max-width: 900px) {
        .workers-grid,
        .quick-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="workers-grid">
    <div class="stat-card">
        <div class="stat-label">Trabajadores registrados</div>
        <div class="stat-number">{{ $totalTrabajadores }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Trabajadores activos</div>
        <div class="stat-number">{{ $trabajadoresActivos }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Trabajadores inactivos</div>
        <div class="stat-number">{{ $trabajadoresInactivos }}</div>
    </div>
</div>

<div class="quick-register" id="registroTrabajador">
    <div class="quick-card">
        <div class="quick-header">
            <div class="quick-icon">☻</div>
            <div>
                <h2>Registrar trabajador rápido</h2>
                <p>Solo ingresa el nombre del trabajador. Se guardará como mesero activo.</p>
            </div>
        </div>

        <form action="{{ route('trabajadores.store') }}" method="POST" class="quick-form">
            @csrf

            <div>
                <label>Nombre del trabajador <span class="required">*</span></label>
                <input
                    class="input"
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    placeholder="Ejemplo: Juan Pérez"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar trabajador
            </button>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-header">
        <div>
            <h2>Lista de trabajadores</h2>
            <p>Visualiza los meseros o trabajadores registrados en SOL & LUNA.</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Trabajador</th>
                <th>Cargo</th>
                <th>Estado</th>
                <th>Registrado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($trabajadores as $trabajador)
                <tr>
                    <td>
                        <div class="worker-name">
                            <div class="worker-avatar">
                                {{ strtoupper(substr($trabajador->nombre, 0, 1)) }}
                            </div>
                            {{ $trabajador->nombre }}
                        </div>
                    </td>

                    <td>{{ $trabajador->cargo }}</td>

                    <td>
                        @if($trabajador->estado)
                            <span class="badge ok">Activo</span>
                        @else
                            <span class="badge danger">Inactivo</span>
                        @endif
                    </td>

                    <td>
                        {{ $trabajador->created_at ? $trabajador->created_at->format('d/m/Y') : '-' }}
                    </td>

                    <td>
                        @if($trabajador->estado)
                            <form action="{{ route('trabajadores.destroy', $trabajador) }}" method="POST" class="action-form" onsubmit="return confirm('¿Deseas desactivar este trabajador?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <span style="color:#6f7f91;">Sin acciones</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            Todavía no registraste trabajadores.
                            <br>
                            Presiona “Registrar trabajador rápido” para agregar el primero.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding: 18px 26px;">
        {{ $trabajadores->links() }}
    </div>
</div>

<script>
    function toggleRegistroTrabajador() {
        const panel = document.getElementById('registroTrabajador');
        panel.classList.toggle('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            const panel = document.getElementById('registroTrabajador');
            if (panel) {
                panel.classList.add('active');
            }
        @endif
    });
</script>

@endsection