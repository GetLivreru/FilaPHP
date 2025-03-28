<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin')),
            'is_admin' => true,
        ]);
    }
}
