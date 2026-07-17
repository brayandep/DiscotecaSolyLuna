<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Trabajador;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\MovimientoStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $productos = Producto::where('estado', true)
            ->where('stock_actual', '>', 0)
            ->orderBy('nombre')
            ->get();

        $trabajadores = Trabajador::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $ventasHoy = Venta::with('trabajador')
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->take(8)
            ->get();

        $totalHoy = Venta::whereDate('created_at', now()->toDateString())->sum('total');

        $efectivoHoy = Venta::whereDate('created_at', now()->toDateString())
            ->where('estado_pago', 'Pagado')
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto_pagado');

        $qrHoy = Venta::whereDate('created_at', now()->toDateString())
            ->where('estado_pago', 'Pagado')
            ->where('metodo_pago', 'QR')
            ->sum('monto_pagado');

        $fiadoHoy = Venta::whereDate('created_at', now()->toDateString())
            ->where('estado_pago', 'Fiado')
            ->sum('saldo_pendiente');

        return view('ventas.index', compact(
            'productos',
            'trabajadores',
            'ventasHoy',
            'totalHoy',
            'efectivoHoy',
            'qrHoy',
            'fiadoHoy'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'estado_pago' => 'required|in:Pagado,Fiado',
            'metodo_pago' => 'nullable|in:Efectivo,QR',
            'producto_ids' => 'required|array|min:1',
            'producto_ids.*' => 'required|exists:productos,id',
            'cantidades' => 'required|array|min:1',
            'cantidades.*' => 'required|integer|min:1',
            'observacion' => 'nullable|string|max:500',
            'con_acompanamiento' => 'nullable|array',
'con_acompanamiento.*' => 'nullable|in:0,1',
        ]);

        if ($request->estado_pago === 'Pagado' && !$request->metodo_pago) {
            return back()->withErrors([
                'metodo_pago' => 'Debes seleccionar si el pago fue en efectivo o QR.',
            ])->withInput();
        }

        $items = [];

foreach ($request->producto_ids as $index => $productoId) {
    $cantidad = intval($request->cantidades[$index] ?? 0);
    $conAcompanamiento = intval($request->con_acompanamiento[$index] ?? 0);

    if ($cantidad > 0) {
        $key = $productoId . '_' . $conAcompanamiento;

        if (!isset($items[$key])) {
            $items[$key] = [
                'producto_id' => $productoId,
                'cantidad' => 0,
                'con_acompanamiento' => $conAcompanamiento,
            ];
        }

        $items[$key]['cantidad'] += $cantidad;
    }
}

        if (count($items) === 0) {
            return back()->withErrors([
                'productos' => 'Debes agregar al menos un producto a la venta.',
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $trabajador = Trabajador::findOrFail($request->trabajador_id);

$productosIds = collect($items)->pluck('producto_id')->unique()->values();

$productos = Producto::whereIn('id', $productosIds)                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;

foreach ($items as $item) {
    $producto = $productos[$item['producto_id']] ?? null;
    $cantidad = $item['cantidad'];
    $conAcompanamiento = $item['con_acompanamiento'];

    if (!$producto) {
        throw new \Exception('Uno de los productos no existe.');
    }

    if (!$producto->estado) {
        throw new \Exception('El producto ' . $producto->nombre . ' no está disponible.');
    }

    if ($producto->stock_actual < $cantidad) {
        throw new \Exception('Stock insuficiente para ' . $producto->nombre . '. Stock actual: ' . $producto->stock_actual);
    }

    $precioAcompanamiento = $conAcompanamiento ? $producto->precio_acompanamiento : 0;
    $precioFinal = $producto->precio_venta + $precioAcompanamiento;

    $total += $precioFinal * $cantidad;
}
            $estadoPago = $request->estado_pago;
            $metodoPago = $estadoPago === 'Pagado' ? $request->metodo_pago : null;
            $montoPagado = $estadoPago === 'Pagado' ? $total : 0;
            $saldoPendiente = $estadoPago === 'Fiado' ? $total : 0;

            $venta = Venta::create([
                'trabajador_id' => $trabajador->id,
                'user_id' => Auth::id(),
                'total' => $total,
                'estado_pago' => $estadoPago,
                'metodo_pago' => $metodoPago,
                'monto_pagado' => $montoPagado,
                'saldo_pendiente' => $saldoPendiente,
                'observacion' => $request->observacion,
            ]);

            foreach ($items as $item) {
    $producto = $productos[$item['producto_id']];
    $cantidad = $item['cantidad'];
    $conAcompanamiento = $item['con_acompanamiento'];

    $precioAcompanamiento = $conAcompanamiento ? $producto->precio_acompanamiento : 0;
    $nombreAcompanamiento = $conAcompanamiento ? $producto->nombre_acompanamiento : null;

    $precioFinal = $producto->precio_venta + $precioAcompanamiento;
    $subtotal = $precioFinal * $cantidad;

    VentaDetalle::create([
        'venta_id' => $venta->id,
        'producto_id' => $producto->id,
        'cantidad' => $cantidad,
        'precio_unitario' => $precioFinal,
        'con_acompanamiento' => $conAcompanamiento,
        'nombre_acompanamiento' => $nombreAcompanamiento,
        'precio_acompanamiento' => $precioAcompanamiento,
        'subtotal' => $subtotal,
    ]);

    $stockAnterior = $producto->stock_actual;
    $stockNuevo = $stockAnterior - $cantidad;

    $producto->update([
        'stock_actual' => $stockNuevo,
    ]);

    MovimientoStock::create([
        'producto_id' => $producto->id,
        'tipo_movimiento' => 'Salida',
        'cantidad' => $cantidad,
        'stock_anterior' => $stockAnterior,
        'stock_nuevo' => $stockNuevo,
        'motivo' => 'Venta cargada a ' . $trabajador->nombre . ' - Venta #' . $venta->id,
        'user_id' => Auth::id(),
    ]);
}

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'venta' => $e->getMessage(),
            ])->withInput();
        }
    }
}