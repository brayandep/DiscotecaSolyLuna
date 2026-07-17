<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcompanamientoToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('nombre_acompanamiento')->nullable()->after('precio_venta');
            $table->decimal('precio_acompanamiento', 10, 2)->default(0)->after('nombre_acompanamiento');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['nombre_acompanamiento', 'precio_acompanamiento']);
        });
    }
}