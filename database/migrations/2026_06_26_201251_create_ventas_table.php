<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trabajador_id')
                ->constrained('trabajadores')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado_pago', ['Pagado', 'Fiado'])->default('Pagado');
            $table->enum('metodo_pago', ['Efectivo', 'QR'])->nullable();

            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->decimal('saldo_pendiente', 10, 2)->default(0);

            $table->text('observacion')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}