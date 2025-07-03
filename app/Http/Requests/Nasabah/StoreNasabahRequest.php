<?php

namespace App\Http\Requests\Nasabah;

use Illuminate\Foundation\Http\FormRequest;

class StoreNasabahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:nasabahs,email',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'id_card_number' => 'required|string|unique:nasabahs,id_card_number',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'occupation' => 'nullable|string',
            'monthly_income' => 'nullable|numeric',
            'status' => 'required|string',
        ];
    }
}