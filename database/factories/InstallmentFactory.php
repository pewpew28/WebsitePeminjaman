<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'installment_number' => 1,
            'due_date' => now()->addDays(7),
            'principal_amount' => 1000000,
            'interest_amount' => 100000,
            'fine_amount' => 0,
            'total_due_amount' => 1100000,
            'amount_paid' => 0,
            'remaining_amount' => 1100000,
            'status' => 'pending',
            'payment_method' => null,
            'collector_id' => null,
            'notes' => null,
        ];
    }
}
