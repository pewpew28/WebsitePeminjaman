<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Inisialisasi Faker
        $financeUser = User::where('role', 'finance')->first();

        // Buat 20 customer dummy secara manual
        for ($i = 1; $i <= 20; $i++) {
            Nasabah::create([
                'user_id' => $financeUser->id,
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone_number' => $faker->unique()->phoneNumber(),
                'address' => $faker->address(),
                'id_card_number' => $faker->unique()->numerify('################'), // 16 digit angka
                'date_of_birth' => $faker->date('Y-m-d', '-20 years'),
                'gender' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'occupation' => $faker->jobTitle(),
                'monthly_income' => $faker->randomFloat(2, 2000000, 15000000), // 2jt - 15jt
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
