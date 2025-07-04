<?php

namespace Database\Seeders;

use App\Models\CollectorTask;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
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
        // Panggil seeder
        // $this->call(UserSeeder::class);
        // $this->call(SettingSeeder::class);
        // $this->call(NasabahSeeder::class);
        // $this->call(LoanSeeder::class);
        // $this->call(InstallmentSeeder::class);
        // $this->call(CollectorTaskSeeder::class);

        // Create Settings first (dipindah ke atas agar bisa digunakan)
        Setting::create([
            'key' => 'app_name',
            'value' => 'Loan Management System',
        ]);
        $defaultInterestRate = Setting::create([
            'key' => 'default_interest_rate',
            'value' => '20',
        ]);
        $defaultLoanTerm = Setting::create([
            'key' => 'default_loan_term',
            'value' => '70',
        ]);
        Setting::create([
            'key' => 'default_fine_rate',
            'value' => '5',
        ]);
        Setting::create([
            'key' => 'default_installment_count',
            'value' => '10',
        ]);

        // Create Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '1234567890',
            'address' => 'Admin Address',
            'role' => 'admin',
            'last_seen_at' => now(),
        ]);

        $finance = User::create([
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '1234567891',
            'address' => 'Finance Address',
            'role' => 'finance',
            'last_seen_at' => now(),
        ]);

        $collector = User::create([
            'name' => 'Collector User',
            'email' => 'collector@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '1234567892',
            'address' => 'Collector Address',
            'role' => 'collector',
            'last_seen_at' => now(),
        ]);

        $nasabahUsers = [];
        for ($i = 1; $i <= 5; $i++) {
            $nasabahUsers[] = User::create([
                'name' => "Nasabah User $i",
                'email' => "nasabah$i@example.com",
                'password' => Hash::make('password'),
                'phone_number' => '123456789' . (2 + $i), // Unique phone numbers
                'address' => "Nasabah Address $i",
                'role' => 'nasabah',
                'last_seen_at' => now(),
            ]);
        }

        // Create Nasabahs
        $nasabahs = [];
        foreach ($nasabahUsers as $index => $user) {
            $nasabahs[] = Nasabah::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'id_card_number' => 'ID' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'date_of_birth' => Carbon::now()->subYears(25 + ($index * 5))->subDays($index * 50),
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'occupation' => ['Employee', 'Entrepreneur', 'Teacher', 'Driver', 'Merchant'][$index],
                'monthly_income' => 3000000 + ($index * 500000), // Varied income
                'status' => 'active',
            ]);
        }


        // Add some additional settings
        Setting::create([
            'key' => 'company_name',
            'value' => 'PT. Loan Management Indonesia',
        ]);
        Setting::create([
            'key' => 'company_address',
            'value' => 'Jl. Keuangan No. 123, Jakarta',
        ]);
        Setting::create([
            'key' => 'company_phone',
            'value' => '021-12345678',
        ]);
        Setting::create([
            'key' => 'max_loan_amount',
            'value' => '50000000',
        ]);
        Setting::create([
            'key' => 'min_loan_amount',
            'value' => '1000000',
        ]);
    }
}
