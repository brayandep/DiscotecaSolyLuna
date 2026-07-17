<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre',
        'cargo',
        'telefono',
        'estado',
    ];

    public function horasExtras()
    {
        return $this->hasMany(HoraExtra::class);
    }
}