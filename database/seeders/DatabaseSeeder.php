<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@grtech.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@grtech.com',
            'password' => Hash::make('password'),
        ]);
    }
}
