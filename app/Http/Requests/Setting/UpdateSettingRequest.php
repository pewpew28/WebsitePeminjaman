<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'key' => 'sometimes|string|unique:settings,key,' . $this->setting,
            'value' => 'sometimes|string',
        ];
    }
}