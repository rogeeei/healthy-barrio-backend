<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitizenDetailsRequest extends FormRequest
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
            'firstname'                 => 'required|string|max:255',
            'middle_name'               => 'nullable|string|max:255',
            'lastname'                  => 'required|string|max:255',
            'suffix'                    => 'nullable|string|max:255',
            'address'                   => 'required|string|max:255',
            'date_of_birth'             => 'required|date|date_format:Y-m-d',
            'gender'                    => 'required|string',
            'citizen_status'            => 'required|string|max:255',
            'blood_type'                => 'nullable|string|max:255',
            'height'                    => 'string|max:255',
            'weight'                    => 'string|max:255',
            'allergies'                 => 'nullable|string|max:255',
            'condition'                 => 'string|max:255',
            'medication'                => 'string|max:255',
            'emergency_contact_name'    => 'required|max:255',
            'emergency_contact_no'      => 'string|required|max:255',
        ];
    }
}
