<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email' . $this->user,
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'nullable|string',
        ];
    }
}