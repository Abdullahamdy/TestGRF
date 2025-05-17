<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusRequest extends FormRequest
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
            'status' => ['required', 'integer', 'in:1,2,3,0'],
            'schudle_date'   => ['nullable', 'required_if:status,2', 'date', 'date_format:Y-m-d'],
            'schudle_time'   => ['nullable', 'required_if:status,2', 'date_format:H:i'],
        ];
    }
}
