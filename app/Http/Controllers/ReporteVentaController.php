<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteVentaController extends Controller
{
    private function esSuperAdmin()
    {
        return auth()->check() && auth()->user()->role === 'SuperAdmin';
    }

    private function obtenerRango($tipo, $fecha, $turno = null)
{
    try {
        $fechaBase = Carbon::parse($fecha);
    } catch (\Exception $e) {
        $fechaBase = now();
    }

    if ($turno === 'A') {
        $inicio = $fechaBase->copy()->setTime(7, 0, 0);
        $fin = $fechaBase->copy()->setTime(19, 0, 0);
        return [$inicio, $fin];
    }

    if ($turno === 'B') {
        $inicio = $fechaBase->copy()->setTime(19, 15, 0);
        $fin = $fechaBase->copy()->addDay()->setTime(6, 55, 0);
        return [$inicio, $fin];
    }

    if ($tipo === 'semana') {
        $inicio = $fechaBase->copy()->startOfWeek();
        $fin = $fechaBase->copy()->endOfWeek();
    } elseif ($tipo === 'mes') {
        $inicio = $fechaBase->copy()->startOfMonth();
        $fin = $fechaBase->copy()->endOfMonth();
    } else {
        $inicio = $fechaBase->copy()->startOfDay();
        $fin = $fechaBase->copy()->endOfDay();
    }

    return [$inicio, $fin];
}

    private function obtenerDatos(Request $request)
    {
        $tipo = $request->get('tipo', 'dia');
$fecha = $request->get('fecha', now()->toDateString());
$turno = $request->get('turno');
$trabajadorId = $request->get('trabajador_id');
$estadoPago = $request->get('estado_pago');
$metodoPago = $request->get('metodo_pago');

[$inicio, $fin] = $this->obtenerRango($tipo, $fecha, $turno);

        $query = Venta::with(['trabajador', 'detalles.producto', 'usuario'])
            ->whereBetween('created_at', [$inicio, $fin]);

        if ($trabajadorId) {
            $query->where('trabajador_id', $trabajadorId);
        }

        if ($estadoPago) {
            $query->where('estado_pago', $estadoPago);
        }

        if ($metodoPago) {
            $query->where('metodo_pago', $metodoPago);
        }

        $ventas = $query->orderBy('created_at', 'desc')->get();

        $ventasIds = $ventas->pluck('id');

        $totalVentas = $ventas->sum('total');

        $efectivo = $ventas
            ->where('estado_pago', 'Pagado')
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto_pagado');

        $qr = $ventas
            ->where('estado_pago', 'Pagado')
            ->where('metodo_pago', 'QR')
            ->sum('monto_pagado');

        $fiado = $ventas
            ->where('estado_pago', 'Fiado')
            ->sum('saldo_pendiente');

        $cantidadVentas = $ventas->count();

        $productosVendidos = VentaDetalle::whereIn('venta_id', $ventasIds)
            ->sum('cantidad');

        $trabajadores = Trabajador::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $periodoTexto = 'Desde ' . $inicio->format('d/m/Y H:i') . ' hasta ' . $fin->format('d/m/Y H:i');

        $puedeVerTotales = $this->esSuperAdmin();

        return compact(
            'ventas',
            'tipo',
            'fecha',
            'turno',
            'trabajadorId',
            'estadoPago',
            'metodoPago',
            'trabajadores',
            'inicio',
            'fin',
            'periodoTexto',
            'totalVentas',
            'efectivo',
            'qr',
            'fiado',
            'cantidadVentas',
            'productosVendidos',
            'puedeVerTotales'
        );
    }

    public function index(Request $request)
    {
        $data = $this->obtenerDatos($request);

        return view('ventas.reporte', $data);
    }

    public function pdf(Request $request)
    {
        if (!$this->esSuperAdmin()) {
            return redirect()->route('ventas.reporte')
                ->withErrors(['pdf' => 'No tienes permiso para descargar el reporte con totales.']);
        }

        $data = $this->obtenerDatos($request);

       $pdf = Pdf::loadView('ventas.reporte_pdf', $data)
    ->setPaper('letter', 'landscape');

        return $pdf->download('reporte_ventas_' . now()->format('Ymd_His') . '.pdf');
    }
}