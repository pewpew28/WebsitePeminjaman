<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Setting::firstOrCreate(['key' => 'app_name'], ['value' => 'Aplikasi Pinjaman Cepat']);
        Setting::firstOrCreate(['key' => 'interest_rate'], ['value' => '0.015']); // 1.5% per bulan
        Setting::firstOrCreate(['key' => 'loan_term_unit'], ['value' => 'months']);
        Setting::firstOrCreate(['key' => 'default_loan_term'], ['value' => '12']); // 12 bulan
        Setting::firstOrCreate(['key' => 'fine_rate'], ['value' => '0.001']); // 0.1% per hari
        Setting::firstOrCreate(['key' => 'fine_unit'], ['value' => 'daily']);
        Setting::firstOrCreate(['key' => 'company_address'], ['value' => 'Jl. Contoh No. 123, Kota Contoh']);
        Setting::firstOrCreate(['key' => 'company_phone'], ['value' => '021-1234567']);
    }
}
