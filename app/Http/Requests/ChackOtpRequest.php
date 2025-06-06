<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChackOtpRequest extends FormRequest
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
            'phone' => 'required|numeric',
            'code' => 'required|numeric|digits:4',
            'device_token' => ['nullable'],
            'platform' => ['nullable', 'in:ios,android']
        ];
    }
}
