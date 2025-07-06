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
            'phone_number' => '0811-1234-5678',
            'address' => 'Jl. Admin Raya No. 1, Jakarta Pusat',
            'role' => 'admin',
            'last_seen_at' => now(),
        ]);

        $finance = User::create([
            'name' => 'Finance User',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '0812-1234-5678',
            'address' => 'Jl. Keuangan No. 25, Jakarta Selatan',
            'role' => 'finance',
            'last_seen_at' => now(),
        ]);

        // Create 3 Collectors
        $collector1 = User::create([
            'name' => 'Jumiati',
            'email' => 'jumiati@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '0813-1234-5678',
            'address' => 'Jl. Collector No. 10, Medan',
            'role' => 'collector',
            'last_seen_at' => now(),
        ]);
        $collector2 = User::create([
            'name' => 'Revangga',
            'email' => 'revangga@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '0814-1234-5678',
            'address' => 'Jl. Penagih No. 15, Deli Serdang',
            'role' => 'collector',
            'last_seen_at' => now(),
        ]);
        $collector3 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'phone_number' => '0815-1234-5678',
            'address' => 'Jl. Tagihan No. 20, Binjai',
            'role' => 'collector',
            'last_seen_at' => now(),
        ]);

        // Daftar desa/daerah (5 daerah untuk 10 nasabah, masing-masing 2 orang)
        $areas = [
            'Desa Suka Maju, Kec. Medan Baru',
            'Desa Sejahtera, Kec. Deli Tua', 
            'Desa Makmur, Kec. Sunggal',
            'Desa Damai, Kec. Percut Sei Tuan',
            'Desa Berkah, Kec. Hamparan Perak'
        ];

        // Data nasabah yang lebih realistis
        $nasabahData = [
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@example.com', 'occupation' => 'Pedagang', 'income' => 4500000],
            ['name' => 'Ahmad Rizki', 'email' => 'ahmad.rizki@example.com', 'occupation' => 'Petani', 'income' => 3500000],
            ['name' => 'Dewi Sartika', 'email' => 'dewi.sartika@example.com', 'occupation' => 'Guru', 'income' => 5000000],
            ['name' => 'Bambang Susilo', 'email' => 'bambang.susilo@example.com', 'occupation' => 'Sopir', 'income' => 4000000],
            ['name' => 'Rina Wati', 'email' => 'rina.wati@example.com', 'occupation' => 'Penjahit', 'income' => 3800000],
            ['name' => 'Andi Pratama', 'email' => 'andi.pratama@example.com', 'occupation' => 'Montir', 'income' => 4200000],
            ['name' => 'Sari Melati', 'email' => 'sari.melati@example.com', 'occupation' => 'Warung Makan', 'income' => 6000000],
            ['name' => 'Joko Widodo', 'email' => 'joko.widodo@example.com', 'occupation' => 'Buruh Bangunan', 'income' => 3200000],
            ['name' => 'Lina Marlina', 'email' => 'lina.marlina@example.com', 'occupation' => 'Salon', 'income' => 4800000],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@example.com', 'occupation' => 'Bengkel', 'income' => 5500000],
        ];

        // Create 10 Nasabah Users
        $nasabahUsers = [];
        for ($i = 0; $i < 10; $i++) {
            $areaIndex = intval($i / 2); // Setiap 2 orang akan tinggal di daerah yang sama
            $phoneNumber = '0821-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '-' . rand(1000, 9999);
            
            $nasabahUsers[] = User::create([
                'name' => $nasabahData[$i]['name'],
                'email' => $nasabahData[$i]['email'],
                'password' => Hash::make('password'),
                'phone_number' => $phoneNumber,
                'address' => 'Jl. Nasabah No. ' . ($i + 1) . ', ' . $areas[$areaIndex],
                'role' => 'nasabah',
                'last_seen_at' => now(),
            ]);
        }

        // Create Nasabahs
        $nasabahs = [];
        foreach ($nasabahUsers as $index => $user) {
            $areaIndex = intval($index / 2);
            $genders = ['male', 'female'];
            $randomGender = $genders[array_rand($genders)];
            
            $nasabahs[] = Nasabah::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'id_card_number' => '1275' . str_pad($index + 1, 6, '0', STR_PAD_LEFT) . '0001',
                'date_of_birth' => Carbon::now()->subYears(rand(25, 55))->subDays(rand(1, 365)),
                'gender' => $randomGender,
                'occupation' => $nasabahData[$index]['occupation'],
                'monthly_income' => $nasabahData[$index]['income'],
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
            'value' => 'Jl. Keuangan No. 123, Medan, Sumatera Utara',
        ]);
        Setting::create([
            'key' => 'company_phone',
            'value' => '061-12345678',
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