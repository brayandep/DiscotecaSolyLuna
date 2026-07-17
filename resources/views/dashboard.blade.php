@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Resumen general de SOL & LUNA')

@section('content')

<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px;">
    <div class="card">
        <h3>Productos</h3>
        <h1>{{ $totalProductos }}</h1>
    </div>

    <div class="card">
        <h3>Stock bajo</h3>
        <h1>{{ $stockBajo }}</h1>
    </div>

    <div class="card">
        <h3>Trabajadores activos</h3>
        <h1>{{ $trabajadores }}</h1>
    </div>

    <div class="card">
        <h3>Horas extras pagadas</h3>
        <h1>Bs {{ number_format($totalHorasExtras, 2) }}</h1>
    </div>
</div>

@endsection