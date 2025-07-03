<?php

namespace App\Http\Requests\Nasabah;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNasabahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:nasabahs,email,' . $this->nasabah,
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'id_card_number' => 'sometimes|string|unique:nasabahs,id_card_number,' . $this->nasabah,
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'occupation' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'status' => 'sometimes|string',
        ];
    }
}