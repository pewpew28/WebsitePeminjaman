<?php

namespace App\Http\Requests\CollectorTask;

use Illuminate\Foundation\Http\FormRequest;

class RecordCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'amount_collected' => 'required|numeric|min:0',
            'status' => 'sometimes|string',
            'notes' => 'nullable|string',
        ];
    }
}