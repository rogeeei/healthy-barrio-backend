<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CitizenHistory;

class DiagnosticRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<string>|string>
     */
    public function rules(): array
    {
        return [
            'diagnosis'           => 'required|string|max:255',
            'date'                => 'nullable|date|date_format:Y-m-d',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'diagnosis.required' => 'The diagnostic name is required.',
            'date.date'          => 'The diagnosis date must be a valid date.',
        ];
    }

    public function citizenHistory()
    {
        return $this->belongsTo(CitizenHistory::class, 'citizen_history_id'); // Ensure the correct foreign key
    }
}
