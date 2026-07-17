<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcompanamientoToVentaDetallesTable extends Migration
{
    public function up()
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->boolean('con_acompanamiento')->default(false)->after('precio_unitario');
            $table->string('nombre_acompanamiento')->nullable()->after('con_acompanamiento');
            $table->decimal('precio_acompanamiento', 10, 2)->default(0)->after('nombre_acompanamiento');
        });
    }

    public function down()
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropColumn([
                'con_acompanamiento',
                'nombre_acompanamiento',
                'precio_acompanamiento'
            ]);
        });
    }
}