<?php

namespace Database\Seeders;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $loans = Loan::all();
        // Ambil user Collector dan Finance berdasarkan kolom 'role'
        $collectorUser = User::where('role', 'collector')->first();
        $financeUser = User::where('role', 'finance')->first();

        foreach ($loans as $loan) {
            $principalPerInstallment = $loan->loan_amount / $loan->loan_term;
            $interestPerInstallment = ($loan->loan_amount * $loan->interest_rate); // Asumsi bunga flat per bulan

            for ($i = 1; $i <= $loan->loan_term; $i++) {
                // Pastikan due_date adalah instance Carbon
                $dueDate = Carbon::parse($loan->start_date)->addMonths($i);
                $totalDueAmount = $principalPerInstallment + $interestPerInstallment;

                $status = 'unpaid';
                $amountPaid = 0;
                $paymentDate = null;
                $paidByUserId = null;
                $paymentMethod = null;

                // Simulasi pembayaran
                if (fake()->boolean(70)) { // 70% kemungkinan sudah dibayar
                    $status = fake()->randomElement(['paid', 'partially_paid']);
                    $amountPaid = ($status === 'paid') ? $totalDueAmount : fake()->randomFloat(2, 0.1 * $totalDueAmount, 0.9 * $totalDueAmount);
                    // Pastikan payment_date adalah instance Carbon
                    $paymentDate = Carbon::parse(fake()->dateTimeBetween($loan->start_date, 'now'));
                    $paidByUserId = fake()->randomElement([$collectorUser->id, $financeUser->id]);
                    $paymentMethod = fake()->randomElement(['cash', 'transfer']);
                } elseif ($dueDate->lt(Carbon::now())) { // Gunakan Carbon method untuk perbandingan tanggal
                    $status = 'overdue';
                }

                Installment::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_amount' => $principalPerInstallment,
                    'interest_amount' => $interestPerInstallment,
                    'fine_amount' => 0, // Denda dihitung terpisah atau saat pembayaran
                    'total_due_amount' => $totalDueAmount,
                    'amount_paid' => $amountPaid,
                    'payment_date' => $paymentDate,
                    'status' => $status,
                    'paid_by_user_id' => $paidByUserId,
                    'payment_method' => $paymentMethod,
                    'notes' => fake()->sentence(),
                ]);
            }
        }
    }
}
