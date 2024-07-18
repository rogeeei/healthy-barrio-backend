<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                       => 'required|string|max:255',
            'usage_description'          => 'required|string|max:255',
            'quantity'                   => 'required|integer|max:255',
            'expiration_date'            => 'required||date|date_format:Y-m-d',
            'batch_no'                   => 'required|string|max:255',
            'location'                   => 'required|string|max:255',
            'medicine_status'            => 'required|string|max:255',
        ];
    }
}
