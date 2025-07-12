<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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


    public function rules()
    {
        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name'          => 'required|string|min:3|max:50|unique:roles,name',
                        'permissions'   => 'required|array',
                        'permissions.*' => 'required|string|min:3|max:150|exists:permissions,name',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisRole = $this->route('role');
                    return [
                        'name'          => 'required|string|min:3|max:50|unique:roles,name,' . $thisRole,
                        'permissions'   => 'required|array',
                        'permissions.*' => 'required|string|min:3|max:150|exists:permissions,name',
                    ];
                }
            default:
                break;
        }
    }
}
