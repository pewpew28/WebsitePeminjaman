<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $finance = User::create([
            'name' => 'Finance',
            'email' => 'finance@example.com',
            'password' => bcrypt('password'),
            'role' => 'finance',
        ]);

        $collector = User::create([
            'name' => 'Collector',
            'email' => 'collector@example.com',
            'password' => bcrypt('password'),
            'role' => 'collector',
        ]);

        $nasabah = User::create([
            'name' => 'Nasabah',
            'email' => 'nasabah@example.com',
            'password' => bcrypt('password'),
            'role' => 'nasabah',
        ]);
    }
}
