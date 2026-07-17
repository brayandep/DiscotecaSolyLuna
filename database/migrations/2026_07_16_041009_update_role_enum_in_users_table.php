<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateRoleEnumInUsersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('SuperAdmin', 'Administrador', 'Cajero', 'Encargado') DEFAULT 'Administrador'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('Administrador', 'Cajero', 'Encargado') DEFAULT 'Administrador'");
    }
}