<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajadores = Trabajador::orderBy('estado', 'desc')
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        $totalTrabajadores = Trabajador::count();
        $trabajadoresActivos = Trabajador::where('estado', true)->count();
        $trabajadoresInactivos = Trabajador::where('estado', false)->count();

        return view('trabajadores.index', compact(
            'trabajadores',
            'totalTrabajadores',
            'trabajadoresActivos',
            'trabajadoresInactivos'
        ));
    }

    public function create()
    {
        return redirect()->route('trabajadores.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Trabajador::create([
            'nombre' => $request->nombre,
            'cargo' => 'Mesero',
            'telefono' => null,
            'estado' => true,
        ]);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador registrado correctamente.');
    }

    public function edit(Trabajador $trabajadore)
    {
        return redirect()->route('trabajadores.index');
    }

    public function update(Request $request, Trabajador $trabajadore)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $trabajadore->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador actualizado correctamente.');
    }

    public function destroy(Trabajador $trabajadore)
    {
        $trabajadore->update([
            'estado' => false,
        ]);

        return redirect()->route('trabajadores.index')
            ->with('success', 'Trabajador desactivado correctamente.');
    }
}