@extends('layouts.app')

@section('title', 'Stock de productos')
@section('subtitle', 'Controla bebidas, snacks, precios y cantidades disponibles.')

@section('content')
@php
    $puedeEditarProductos = auth()->check() && auth()->user()->role === 'SuperAdmin';
@endphp
<div class="card">
    <form method="GET" action="{{ route('productos.index') }}" style="display:flex; gap:10px;">
        <input class="input" type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar producto...">
        <button class="btn">Buscar</button>
        <a class="btn btn-gold" href="{{ route('productos.create') }}">+ Registrar producto</a>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Mínimo</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria }}</td>
                    <td>Bs {{ number_format($producto->precio_venta, 2) }}</td>
                    <td>{{ $producto->stock_actual }} {{ $producto->unidad }}</td>
                    <td>{{ $producto->stock_minimo }}</td>
                    <td>
                        @if($producto->estado_stock == 'Disponible')
                            <span class="badge ok">Disponible</span>
                        @elseif($producto->estado_stock == 'Stock bajo')
                            <span class="badge warning">Stock bajo</span>
                        @else
                            <span class="badge danger">Agotado</span>
                        @endif
                    </td>
                    <td>
    @if($producto->imagen)
        <img src="{{ asset('storage/' . $producto->imagen) }}" 
             alt="{{ $producto->nombre }}" 
             style="width:55px; height:55px; object-fit:cover; border-radius:12px;">
    @else
        <div style="width:55px; height:55px; border-radius:12px; background:#eef4fa; display:flex; align-items:center; justify-content:center; color:#6f7f91;">
            Sin
        </div>
    @endif
</td>
                   <td>
    @if($puedeEditarProductos)
    <a class="btn btn-edit" href="{{ route('productos.edit', $producto) }}">
        ✎ Editar
    </a>
@endif

    <a class="btn btn-gold" href="{{ route('productos.ajustar.form', $producto) }}">Agregar</a>
</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    {{ $productos->links() }}
</div>

@endsection