<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\HoraExtraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteVentaController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('productos', ProductoController::class);
    Route::get('/productos/{producto}/ajustar-stock', [ProductoController::class, 'ajustarForm'])->name('productos.ajustar.form');
    Route::post('/productos/{producto}/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('productos.ajustar');
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');

    Route::resource('trabajadores', TrabajadorController::class)->except(['show']);
    Route::resource('horas-extras', HoraExtraController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::get('/ventas/reporte', [ReporteVentaController::class, 'index'])->name('ventas.reporte');
Route::get('/ventas/reporte/pdf', [ReporteVentaController::class, 'pdf'])->name('ventas.reporte.pdf');

Route::get('/horas-extras/pdf', [HoraExtraController::class, 'pdf'])->name('horas-extras.pdf');
Route::resource('horas-extras', HoraExtraController::class)->except(['show', 'edit', 'update', 'destroy']);
    });