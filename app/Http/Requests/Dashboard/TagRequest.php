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

                        'translations.en.name' => 'required|string|max:255',
                        'translations.en.description'  => 'required|string|max:255',
                        'translations.ar.name' => 'required|string|max:255',
                        'translations.ar.description'  => 'required|string|max:255',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisTag = $this->route('tag');
                    return [
                        'translations.en.name' => 'required|string|max:255',
                        'translations.en.description'  => 'required|string|max:255',
                        'translations.ar.name' => 'required|string|max:255',
                        'translations.ar.description'  => 'required|string|max:255',
                    ];
                }
            default:
                break;
        }
    }
}
