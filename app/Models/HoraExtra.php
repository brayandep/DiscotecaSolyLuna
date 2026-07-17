<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoraExtra extends Model
{
    protected $table = 'hora_extras';

    protected $fillable = [
        'trabajador_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'horas_calculadas',
        'nro_veces',
        'tipo_tarifa',
        'monto_pagado',
        'estado_pago',
        'saldo_pendiente',
        'metodo_pago',
        'observacion',
        'user_id',
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}