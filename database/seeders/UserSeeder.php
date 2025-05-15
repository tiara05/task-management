<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true,
        ]);

        User::factory(5)->create([
            'role' => 'staff',
            'status' => true,
        ]);

        User::factory(2)->create([
            'role' => 'manager',
            'status' => true,
        ]);
    }
}
