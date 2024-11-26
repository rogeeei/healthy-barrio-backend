<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UserRequest extends FormRequest
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
        if (request()->routeIs('user.login')) {
            return [
                'user_id'            => 'required|integer|max:255',
                'password'           => 'required|min:8',
                'brgy'               => 'required|string|max:255',
                'role'               => 'required|in:admin,user',
            ];
        } else if (request()->routeIs('user.store')) {
            return [
                'email'              => 'required|email|string|max:255|unique:App\Models\User,email',
                'password'           => 'required|min:8|confirmed',
                'firstname'          => 'required|string|max:255',
                'middle_name'        => 'nullable|string|max:255',
                'lastname'           => 'required|string|max:255',
                'suffix'             => 'nullable|string|max:255',
                'phone_number'       => 'required|string',
                'brgy'               => 'required|string|max:255',
                'birthdate'          => 'nullable|date|date_format:Y-m-d',
                'role'               => 'required|in:admin,user',
                'image_path'         => 'nullable|max:255',
                'approved'           => 'nullable|boolean',



            ];
            // } else if (request()->routeIs('user.update')) {
            //     return [
            //         'name'        => 'required|string|max:255',

            //     ];
            // } else if (request()->routeIs('user.email')) {
            //     return [
            //         'email'       => 'nullable|string|email|max:255',

            // ];
        } else if (request()->routeIs('user.password')) {
            return [
                'password'    => 'required|confirmed|min:8',

            ];
        }
    }
}
