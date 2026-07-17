<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DuenaSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'duena'],
            [
                'name' => 'Dueña SOL & LUNA',
                'username' => 'elizabeth',
                'password' => Hash::make('77412345a'),
                'role' => 'SuperAdmin',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}