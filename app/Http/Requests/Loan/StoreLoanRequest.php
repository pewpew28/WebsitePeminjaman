<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0',
            'loan_term' => 'required|integer|min:1',
            'term_unit' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string',
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
            'is_refinanced' => 'boolean',
            'original_loan_id' => 'nullable|exists:loans,id',
        ];
    }
}