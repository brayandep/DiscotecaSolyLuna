@extends('layouts.app')

@section('title', 'Ajustar stock')
@section('subtitle', 'Entrada, salida o ajuste manual de producto.')

@section('content')

<div class="card">
    <h3>{{ $producto->nombre }}</h3>
    <p>Stock actual: <strong>{{ $producto->stock_actual }} {{ $producto->unidad }}</strong></p>

    <form action="{{ route('productos.ajustar', $producto) }}" method="POST">
        @csrf

        <label>Tipo de movimiento</label>
        <select name="tipo_movimiento">
            <option value="Entrada">Entrada</option>
            
        </select>

        <label>Cantidad</label>
        <input class="input" type="number" name="cantidad" min="0">

        <label>Motivo</label>
        <input class="input" type="text" name="motivo" placeholder="Ej: Compra, rotura, ajuste de inventario">

        <button class="btn btn-gold">Guardar ajuste</button>
    </form>
</div>

@endsection