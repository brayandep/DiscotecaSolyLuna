<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'trabajador_id',
        'user_id',
        'total',
        'estado_pago',
        'metodo_pago',
        'monto_pagado',
        'saldo_pendiente',
        'observacion',
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}