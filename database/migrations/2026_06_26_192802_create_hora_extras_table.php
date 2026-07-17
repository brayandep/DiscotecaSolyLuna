<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoraExtrasTable extends Migration
{
    public function up()
    {
        Schema::create('hora_extras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trabajador_id')
                ->constrained('trabajadores')
                ->onDelete('cascade');

            $table->date('fecha');
            $table->time('hora_entrada');
            $table->time('hora_salida');

            $table->decimal('horas_calculadas', 8, 2)->default(0);
            $table->integer('nro_veces')->default(1);

            $table->decimal('monto_pagado', 10, 2);
            $table->enum('metodo_pago', ['Efectivo', 'QR']);

            $table->text('observacion')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hora_extras');
    }
}