<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\MovimientoStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    private function puedeEditarProductos()
{
    return auth()->check() && auth()->user()->role === 'SuperAdmin';
}
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $productos = Producto::when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'LIKE', "%{$buscar}%")
                      ->orWhere('categoria', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('productos.index', compact('productos', 'buscar'));
    }

    public function create()
    {
        return view('productos.create');
    }
public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'categoria' => 'required|string|max:255',
        'precio_venta' => 'required|numeric|min:0',
        'stock_actual' => 'required|integer|min:0',
        'stock_minimo' => 'required|integer|min:0',
        'unidad' => 'required|string|max:100',
        'descripcion' => 'nullable|string|max:500',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'nombre_acompanamiento' => 'nullable|string|max:100',
'precio_acompanamiento' => 'nullable|numeric|min:0',
        ]);

    $rutaImagen = null;

    if ($request->hasFile('imagen')) {
        $rutaImagen = $request->file('imagen')->store('productos', 'public');
    }

    $producto = Producto::create([
    'nombre' => $request->nombre,
    'categoria' => $request->categoria,
    'precio_venta' => $request->precio_venta,
    'nombre_acompanamiento' => $request->nombre_acompanamiento,
    'precio_acompanamiento' => $request->precio_acompanamiento ?? 0,
    'stock_actual' => $request->stock_actual,
    'stock_minimo' => $request->stock_minimo,
    'unidad' => $request->unidad,
    'descripcion' => $request->descripcion,
    'imagen' => $rutaImagen,
    'estado' => $request->has('estado'),
]);

    MovimientoStock::create([
        'producto_id' => $producto->id,
        'tipo_movimiento' => 'Entrada',
        'cantidad' => $producto->stock_actual,
        'stock_anterior' => 0,
        'stock_nuevo' => $producto->stock_actual,
        'motivo' => 'Stock inicial',
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('productos.index')
        ->with('success', 'Producto registrado correctamente.');
}

    public function edit(Producto $producto)
{
    if (!$this->puedeEditarProductos()) {
        abort(403, 'No tienes permiso para editar productos.');
    }

    return view('productos.edit', compact('producto'));
}
    public function update(Request $request, Producto $producto)
{
    if (!$this->puedeEditarProductos()) {
        abort(403, 'No tienes permiso para actualizar productos.');
    }

    $request->validate([
        'nombre' => 'required|string|max:255',
        'categoria' => 'required|string|max:255',
        'precio_venta' => 'required|numeric|min:0',
        'nombre_acompanamiento' => 'nullable|string|max:100',
        'precio_acompanamiento' => 'nullable|numeric|min:0',
        'stock_minimo' => 'required|integer|min:0',
        'unidad' => 'required|string|max:100',
        'descripcion' => 'nullable|string|max:500',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $rutaImagen = $producto->imagen;

    if ($request->hasFile('imagen')) {
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $rutaImagen = $request->file('imagen')->store('productos', 'public');
    }

    $producto->update([
        'nombre' => $request->nombre,
        'categoria' => $request->categoria,
        'precio_venta' => $request->precio_venta,
        'nombre_acompanamiento' => $request->nombre_acompanamiento,
        'precio_acompanamiento' => $request->precio_acompanamiento ?? 0,
        'stock_minimo' => $request->stock_minimo,
        'unidad' => $request->unidad,
        'descripcion' => $request->descripcion,
        'imagen' => $rutaImagen,
        'estado' => $request->has('estado'),
    ]);

    return redirect()->route('productos.index')
        ->with('success', 'Producto actualizado correctamente.');
}

    public function ajustarForm(Producto $producto)
    {
        return view('productos.ajustar', compact('producto'));
    }

    public function ajustarStock(Request $request, Producto $producto)
    {
        $request->validate([
            'tipo_movimiento' => 'required|in:Entrada,Salida,Ajuste',
            'cantidad' => 'required|integer|min:0',
            'motivo' => 'nullable|string|max:255',
        ]);

        $stockAnterior = $producto->stock_actual;

        if ($request->tipo_movimiento === 'Entrada') {
            $stockNuevo = $stockAnterior + $request->cantidad;
        } elseif ($request->tipo_movimiento === 'Salida') {
            $stockNuevo = $stockAnterior - $request->cantidad;

            if ($stockNuevo < 0) {
                return back()->withErrors([
                    'cantidad' => 'No puedes sacar más productos de los que hay en stock.',
                ]);
            }
        } else {
            $stockNuevo = $request->cantidad;
        }

        $producto->update([
            'stock_actual' => $stockNuevo,
        ]);

        MovimientoStock::create([
            'producto_id' => $producto->id,
            'tipo_movimiento' => $request->tipo_movimiento,
            'cantidad' => $request->cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $stockNuevo,
            'motivo' => $request->motivo,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Stock actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->update(['estado' => false]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto desactivado correctamente.');
    }
}