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
        for ($i = 1; $i <= 3; $i++) {
            $nasabahUsers[] = User::create([
                'name' => "Nasabah User $i",
                'email' => "nasabah$i@example.com",
                'password' => Hash::make('password'),
                'phone_number' => '123456789' . (2 + $i), // Unique: 1234567893, 1234567894, 1234567895
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
                'date_of_birth' => Carbon::now()->subYears(30)->subDays($index * 100),
                'gender' => $index % 2 == 0 ? 'male' : 'female',
                'occupation' => 'Employee',
                'monthly_income' => 5000000,
                'status' => 'active',
            ]);
        }

        // Create Loans and Installments
        foreach ($nasabahs as $nasabah) {
            $loanStatuses = ['completed', 'completed', 'active'];
            foreach ($loanStatuses as $index => $status) {
                $loanAmount = 10000000; // 10,000,000
                $interestRate = 20; // 20%
                $totalInterest = $loanAmount * ($interestRate / 100); // Simple interest: 2,000,000
                $loanTerm = 70; // 70 days
                $installmentCount = 10; // 10 weekly installments
                $principalPerInstallment = $loanAmount / $installmentCount; // 1,000,000
                $interestPerInstallment = $totalInterest / $installmentCount; // 200,000
                $totalDuePerInstallment = $principalPerInstallment + $interestPerInstallment; // 1,200,000

                $startDate = Carbon::now()->subDays(100 - ($index * 30));
                $endDate = $startDate->copy()->addDays($loanTerm);

                $loan = Loan::create([
                    'nasabah_id' => $nasabah->id,
                    'loan_amount' => $loanAmount,
                    'interest_rate' => $interestRate,
                    'loan_term' => $loanTerm,
                    'term_unit' => 'days',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                    'approved_by' => $admin->id,
                    'disbursement_date' => $startDate,
                    'fine_rate' => 0,
                    'fine_unit' => 'percentage', // Fixed: Set to 'percentage' instead of null
                    'total_principal_paid' => $status === 'completed' ? $loanAmount : 0,
                    'total_interest_paid' => $status === 'completed' ? $totalInterest : 0,
                    'total_fines_paid' => 0,
                    'total_amount_with_interest' => $loanAmount + $totalInterest,
                    'remaining_principal' => $status === 'completed' ? 0 : $loanAmount,
                    'remaining_interest' => $status === 'completed' ? 0 : $totalInterest,
                    'remaining_fines' => 0,
                    'is_refinanced' => false,
                    'original_loan_id' => null,
                ]);

                // Create Installments
                for ($i = 1; $i <= $installmentCount; $i++) {
                    $dueDate = $startDate->copy()->addDays($i * 7); // Weekly installments
                    Installment::create([
                        'loan_id' => $loan->id,
                        'installment_number' => $i,
                        'due_date' => $dueDate,
                        'principal_amount' => $principalPerInstallment,
                        'interest_amount' => $interestPerInstallment,
                        'fine_amount' => 0,
                        'total_due_amount' => $totalDuePerInstallment,
                        'amount_paid' => $status === 'completed' ? $totalDuePerInstallment : 0,
                        'payment_date' => $status === 'completed' ? $dueDate : null,
                        'status' => $status === 'completed' ? 'paid' : 'pending',
                        'paid_by_user_id' => $status === 'completed' ? $nasabah->user_id : null,
                        'payment_method' => $status === 'completed' ? 'bank_transfer' : null,
                        'notes' => null,
                    ]);
                }

                // Create Collector Task for active loans
                if ($status === 'active') {
                    CollectorTask::create([
                        'collector_id' => $collector->id,
                        'nasabah_id' => $nasabah->id,
                        'loan_id' => $loan->id,
                        'assigned_date' => now(),
                        'due_date' => now()->addDays(7),
                        'status' => 'pending',
                        'notes' => 'Collect installment for loan #' . $loan->id,
                        'visit_confirmation_qr_data' => null,
                        'actual_visit_date' => null,
                        'amount_collected_during_task' => 0,
                    ]);
                }
            }
        }

        // Create Settings
        Setting::create([
            'key' => 'app_name',
            'value' => 'Loan Management System',
        ]);
        Setting::create([
            'key' => 'default_interest_rate',
            'value' => '20',
        ]);
        Setting::create([
            'key' => 'default_loan_term',
            'value' => '70',
        ]);
    }
}
