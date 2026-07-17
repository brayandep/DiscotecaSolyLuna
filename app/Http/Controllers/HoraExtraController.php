<?php

namespace App\Http\Controllers;

use App\Models\HoraExtra;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HoraExtraController extends Controller
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
   
    private function obtenerDatos(Request $request, $paginar = true)
    {
        $trabajadores = Trabajador::where('estado', true)
            ->orderBy('nombre')
            ->get();

        $tipo = $request->get('tipo', 'dia');
$fecha = $request->get('fecha', now()->toDateString());
$turno = $request->get('turno');
$trabajadorId = $request->get('trabajador_id');
$estadoPago = $request->get('estado_pago');
$metodoPago = $request->get('metodo_pago');

[$inicio, $fin] = $this->obtenerRango($tipo, $fecha, $turno);

        $query = HoraExtra::with(['trabajador', 'usuario'])
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

        $resumenQuery = clone $query;

        if ($paginar) {
            $horasExtras = $query->latest()->paginate(10)->appends($request->query());
        } else {
            $horasExtras = $query->latest()->get();
        }

        $registrosResumen = $resumenQuery->get();

        $totalPagado = $registrosResumen->where('estado_pago', 'Pagado')->sum('monto_pagado');
        $totalFiado = $registrosResumen->where('estado_pago', 'Fiado')->sum('saldo_pendiente');
        $totalEfectivo = $registrosResumen->where('estado_pago', 'Pagado')->where('metodo_pago', 'Efectivo')->sum('monto_pagado');
        $totalQr = $registrosResumen->where('estado_pago', 'Pagado')->where('metodo_pago', 'QR')->sum('monto_pagado');
        $cantidadRegistros = $registrosResumen->count();

        $puedeVerTotales = $this->esSuperAdmin();

        $periodoTexto = 'Desde ' . $inicio->format('d/m/Y H:i') . ' hasta ' . $fin->format('d/m/Y H:i');

       return compact(
    'trabajadores',
    'horasExtras',
    'tipo',
    'fecha',
    'turno',
    'trabajadorId',
    'estadoPago',
    'metodoPago',
    'totalPagado',
    'totalFiado',
    'totalEfectivo',
    'totalQr',
    'cantidadRegistros',
    'puedeVerTotales',
    'periodoTexto'
);
    }

    public function index(Request $request)
    {
        $data = $this->obtenerDatos($request, true);

        return view('horas_extras.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i',
            'tipo_tarifa' => 'nullable|string|max:100',
            'monto_pagado' => 'required|numeric|min:0',
            'estado_pago' => 'required|in:Pagado,Fiado',
            'metodo_pago' => 'nullable|in:Efectivo,QR',
            'observacion' => 'nullable|string|max:500',
        ]);

        if ($request->estado_pago === 'Pagado' && !$request->metodo_pago) {
            return back()->withErrors([
                'metodo_pago' => 'Debes seleccionar si el pago fue en efectivo o QR.',
            ])->withInput();
        }

        $fechaActual = now()->toDateString();

        $entrada = Carbon::parse($fechaActual . ' ' . $request->hora_entrada);
        $salida = Carbon::parse($fechaActual . ' ' . $request->hora_salida);

        if ($salida->lessThanOrEqualTo($entrada)) {
            $salida->addDay();
        }

        $horasCalculadas = round($entrada->diffInMinutes($salida) / 60, 2);
        $monto = floatval($request->monto_pagado);

        HoraExtra::create([
            'trabajador_id' => $request->trabajador_id,
            'fecha' => $fechaActual,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'horas_calculadas' => $horasCalculadas,
            'nro_veces' => 1,
            'tipo_tarifa' => $request->tipo_tarifa,
            'monto_pagado' => $request->estado_pago === 'Pagado' ? $monto : 0,
            'estado_pago' => $request->estado_pago,
            'saldo_pendiente' => $request->estado_pago === 'Fiado' ? $monto : 0,
            'metodo_pago' => $request->estado_pago === 'Pagado' ? $request->metodo_pago : null,
            'observacion' => $request->observacion,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('horas-extras.index')
            ->with('success', 'Hora extra registrada correctamente.');
    }

    public function pdf(Request $request)
    {
        if (!$this->esSuperAdmin()) {
            return redirect()->route('horas-extras.index')
                ->withErrors(['pdf' => 'No tienes permiso para descargar el reporte de horas extras.']);
        }

        $data = $this->obtenerDatos($request, false);

        $pdf = Pdf::loadView('horas_extras.reporte_pdf', $data)
            ->setPaper('letter', 'landscape');

        return $pdf->download('reporte_horas_extras_' . now()->format('Ymd_His') . '.pdf');
    }
}