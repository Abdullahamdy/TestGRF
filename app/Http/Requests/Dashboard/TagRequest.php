<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name' => [
                            'required',
                            'string',
                            'max:255',
                             Rule::unique('tags', 'name'),

                        ],
                        'language' => Rule::requiredIf(fn() => request()->user()->hasRole('admin')),
                        'description' => 'nullable|string|max:1000',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisTag = $this->route('tag');
                    return [
                        'name' => [
                            'required',
                            'string',
                            'max:255',
                            Rule::unique('tags', 'name')->ignore($thisTag),
                        ],
                        'description' => 'nullable|string|max:1000',
                        'type' => 'required|in:1,2',


                    ];
                }
            default:
                break;
        }
    }
}
