<?php

namespace App\Http\Requests\Dashboard\Auth\ForgotPassword;

use Illuminate\Foundation\Http\FormRequest;

class SendCodeRequest extends FormRequest
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
            'phone' => 'required_if:method,phone|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required_if:method,email|nullable|email|max:30',
            'method' => 'required|in:phone,email',

        ];
    }
}
