<?php

namespace App\Http\Requests\Installment;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'loan_id' => 'required|exists:loans,id',
            'installment_number' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'principal_amount' => 'required|numeric|min:0',
            'interest_amount' => 'required|numeric|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'total_due_amount' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|string',
            'paid_by_user_id' => 'nullable|exists:users,id',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}