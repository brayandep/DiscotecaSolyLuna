<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateHoraExtrasPaymentFields extends Migration
{
    public function up()
    {
        Schema::table('hora_extras', function (Blueprint $table) {
            $table->enum('estado_pago', ['Pagado', 'Fiado'])->default('Pagado')->after('monto_pagado');
            $table->decimal('saldo_pendiente', 10, 2)->default(0)->after('estado_pago');
            $table->string('tipo_tarifa')->nullable()->after('nro_veces');
        });

        DB::statement("ALTER TABLE hora_extras MODIFY metodo_pago ENUM('Efectivo', 'QR') NULL");
    }

    public function down()
    {
        Schema::table('hora_extras', function (Blueprint $table) {
            $table->dropColumn(['estado_pago', 'saldo_pendiente', 'tipo_tarifa']);
        });

        DB::statement("ALTER TABLE hora_extras MODIFY metodo_pago ENUM('Efectivo', 'QR') NOT NULL");
    }
}