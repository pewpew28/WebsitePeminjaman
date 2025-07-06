<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nasabah_id' => Nasabah::factory(),
            'loan_amount' => 2000000,
            'interest_rate' => 40,
            'loan_term' => 12,
            'term_unit' => 'month',
            'start_date' => now()->subMonths(1),
            'end_date' => now()->addMonths(11),
            'status' => 'active',
            'approved_by' => null,
            'disbursement_date' => now()->subMonth(),
            'fine_rate' => 5,
            'fine_unit' => 'percentage',
            'total_principal_paid' => 0,
            'total_interest_paid' => 0,
            'total_fines_paid' => 0,
            'total_amount_with_interest' => 2200000,
            'remaining_principal' => 2000000,
            'remaining_interest' => 200000,
            'remaining_fines' => 0,
            'is_refinanced' => false,
            'original_loan_id' => null,
        ];
    }
}
