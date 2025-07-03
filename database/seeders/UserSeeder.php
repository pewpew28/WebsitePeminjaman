<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Inisialisasi Faker

        // Buat user Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'phone_number' => '081234567890',
                'address' => 'Jl. Admin No. 1, Jakarta',
                'password' => Hash::make('password'), // Password default
                'role' => 'admin', // Tetapkan peran langsung
            ]
        );

        // Buat user Finance
        User::firstOrCreate(
            ['email' => 'finance@example.com'],
            [
                'name' => 'Finance User',
                'phone_number' => '081234567891',
                'address' => 'Jl. Finance No. 2, Bandung',
                'password' => Hash::make('password'),
                'role' => 'finance', // Tetapkan peran langsung
            ]
        );

        // Buat user Collector
        User::firstOrCreate(
            ['email' => 'collector@example.com'],
            [
                'name' => 'Collector User',
                'phone_number' => '081234567892',
                'address' => 'Jl. Collector No. 3, Surabaya',
                'password' => Hash::make('password'),
                'role' => 'collector', // Tetapkan peran langsung
            ]
        );

        // Buat user Nasabah (contoh, nasabah akan dibuat melalui CRUD Finance)
        User::firstOrCreate(
            ['email' => 'nasabah@example.com'],
            [
                'name' => 'Nasabah User',
                'phone_number' => '081234567893',
                'address' => 'Jl. Nasabah No. 4, Medan',
                'password' => Hash::make('password'),
                'role' => 'nasabah', // Tetapkan peran langsung
            ]
        );

        // Buat 10 user nasabah dummy lainnya secara manual
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'phone_number' => $faker->unique()->phoneNumber(),
                    'address' => $faker->address(),
                    'password' => Hash::make('password'),
                    'role' => 'nasabah',
                ]
            );
        }
    }
}
