<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone'=>'required_without:user_name|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'user_name'=>'required_without:phone|string|max:30',
            'password'=>'required|string|min:8|max:50',
        ];
    }
}
