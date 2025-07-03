<?php

namespace App\Http\Requests\CollectorTask;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectorTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'collector_id' => 'sometimes|exists:users,id',
            'nasabah_id' => 'sometimes|exists:nasabahs,id',
            'loan_id' => 'nullable|exists:loans,id',
            'assigned_date' => 'sometimes|date',
            'due_date' => 'sometimes|date|after_or_equal:assigned_date',
            'status' => 'sometimes|string',
            'notes' => 'nullable|string',
            'visit_confirmation_qr_data' => 'nullable|string',
            'actual_visit_date' => 'nullable|date',
            'amount_collected_during_task' => 'nullable|numeric|min:0',
        ];
    }
}