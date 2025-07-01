<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customers = Nasabah::all();
        $financeUser = User::where('role', 'finance')->first();

        foreach ($customers as $customer) {
            $loanAmount = fake()->randomFloat(2, 1000000, 10000000); // 1jt - 10jt
            $interestRate = 0.015; // 1.5%
            $loanTerm = fake()->numberBetween(6, 24); // 6-24 bulan
            // Pastikan start_date adalah instance Carbon
            $startDate = Carbon::parse(fake()->dateTimeBetween('-1 year', 'now'));
            $endDate = (clone $startDate)->addMonths($loanTerm);

            // Hitung total_amount_with_interest (contoh sederhana, bisa lebih kompleks)
            $totalAmountWithInterest = $loanAmount + ($loanAmount * $interestRate * $loanTerm);

            Loan::create([
                'nasabah_id' => $customer->id,
                'loan_amount' => $loanAmount,
                'interest_rate' => $interestRate,
                'loan_term' => $loanTerm,
                'term_unit' => 'months',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => fake()->randomElement(['active', 'completed', 'overdue']),
                'approved_by' => $financeUser->id,
                // Pastikan disbursement_date adalah instance Carbon
                'disbursement_date' => Carbon::parse(fake()->dateTimeBetween($startDate, Carbon::now()))->addDays(fake()->numberBetween(1, 7)),
                'fine_rate' => 0.001,
                'fine_unit' => 'daily',
                'total_principal_paid' => 0, // Akan diupdate oleh installment
                'total_interest_paid' => 0,  // Akan diupdate oleh installment
                'total_fines_paid' => 0,     // Akan diupdate oleh installment
                'total_amount_with_interest' => $totalAmountWithInterest,
                'remaining_principal' => $loanAmount,
                'remaining_interest' => $loanAmount * $interestRate * $loanTerm,
                'remaining_fines' => 0,
                'is_refinanced' => false,
            ]);
        }
    }
}
