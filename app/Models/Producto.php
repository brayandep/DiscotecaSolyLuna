<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
    'nombre',
    'categoria',
    'precio_venta',
    'nombre_acompanamiento',
    'precio_acompanamiento',
    'stock_actual',
    'stock_minimo',
    'unidad',
    'descripcion',
    'imagen',
    'estado',
];

    public function movimientos()
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function getEstadoStockAttribute()
    {
        if ($this->stock_actual <= 0) {
            return 'Agotado';
        }

        if ($this->stock_actual <= $this->stock_minimo) {
            return 'Stock bajo';
        }

        return 'Disponible';
    }
}