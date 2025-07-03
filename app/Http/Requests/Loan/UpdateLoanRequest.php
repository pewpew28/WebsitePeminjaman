<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'nasabah_id' => 'sometimes|exists:nasabahs,id',
            'loan_amount' => 'sometimes|numeric|min:0',
            'interest_rate' => 'sometimes|numeric|min:0',
            'loan_term' => 'sometimes|integer|min:1',
            'term_unit' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'status' => 'sometimes|string',
            'approved_by' => 'nullable|exists:users,id',
            'disbursement_date' => 'nullable|date',
            'fine_rate' => 'nullable|numeric|min:0',
            'fine_unit' => 'nullable|string',
            'total_principal_paid' => 'nullable|numeric|min:0',
            'total_interest_paid' => 'nullable|numeric|min:0',
            'total_fines_paid' => 'nullable|numeric|min:0',
            'total_amount_with_interest' => 'nullable|numeric|min:0',
            'remaining_principal' => 'nullable|numeric|min:0',
            'remaining_interest' => 'nullable|numeric|min:0',
            'remaining_fines' => 'nullable|numeric|min:0',
            'is_refinanced' => 'sometimes|boolean',
            'original_loan_id' => 'nullable|exists:loans,id',
        ];
    }
}