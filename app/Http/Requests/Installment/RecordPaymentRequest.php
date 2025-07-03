<?php

namespace App\Http\Requests\Installment;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'amount_paid' => 'required|numeric|min:0',
            'status' => 'sometimes|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'payer_id' => 'required|exists:users,id',
        ];
    }
}