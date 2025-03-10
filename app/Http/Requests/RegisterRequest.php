<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'phone' => ['required', 'numeric', 'digits:9', 'unique:users,phone,except,id'],
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'device_token' => ['nullable'],
            'platform' => ['nullable', 'in:ios,android']
        ];
    }
}
