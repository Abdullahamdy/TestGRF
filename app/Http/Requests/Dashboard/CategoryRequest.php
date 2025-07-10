<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                        'translations.en.name' => 'required|string|max:255',
                        'translations.en.description'  => 'required|string|max:255',
                        'translations.ar.name' => 'required|string|max:255',
                        'translations.ar.description'  => 'required|string|max:255',
                        'parent_id' => 'nullable|exists:categories,id',

                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisCategory = $this->route('category');
                    return [
                        'translations.en.name' => 'required|string|max:255',
                        'translations.en.description'  => 'required|string|max:255',
                        'translations.ar.name' => 'required|string|max:255',
                        'translations.ar.description'  => 'required|string|max:255',
                        'parent_id' => 'nullable|exists:categories,id',

                    ];
                }
            default:
                break;
        }
    }
}
