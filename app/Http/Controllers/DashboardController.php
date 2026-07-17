<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Trabajador;
use App\Models\HoraExtra;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();
        $stockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();
        $trabajadores = Trabajador::where('estado', true)->count();
        $totalHorasExtras = HoraExtra::sum('monto_pagado');

        return view('dashboard', compact(
            'totalProductos',
            'stockBajo',
            'trabajadores',
            'totalHorasExtras'
        ));
    }
}