<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MessageReplayRequest extends FormRequest
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
            'message_replay' => 'nullable|string|max:1000',
            'method' => 'required|in:phone,email',
            'email' => 'required_if:method,email|nullable|email|max:30',
            'phone' => 'required_if:method,phone|nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }
}
