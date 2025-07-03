<?php

namespace App\Http\Requests\Installment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstallmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'loan_id' => 'sometimes|exists:loans,id',
            'installment_number' => 'sometimes|integer|min:1',
            'due_date' => 'sometimes|date',
            'principal_amount' => 'sometimes|numeric|min:0',
            'interest_amount' => 'sometimes|numeric|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'total_due_amount' => 'sometimes|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'sometimes|string',
            'paid_by_user_id' => 'nullable|exists:users,id',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}