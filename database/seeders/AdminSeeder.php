<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Administrador SOL & LUNA',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Administrador',
            'estado' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}