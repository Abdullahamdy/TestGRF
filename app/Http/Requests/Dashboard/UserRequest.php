<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        $isWriter = $this->input('wirters') == 1;

        switch ($this->method()) {
            case 'POST': {
                    return [

                        'user_name' => [
                            Rule::requiredIf(!$isWriter),
                            'string',
                            'regex:/^[a-zA-Z0-9_]+$/',
                            'max:255',
                            'unique:users,user_name',
                        ],
                        'first_name' => 'required|string|max:50|regex:/^[\p{L}\s\-]+$/u',
                        'last_name' => 'required|string|max:50|regex:/^[\p{L}\s\-]+$/u',
                        // 'role' => 'required_without:wirters|string|exists:roles,name',
                        'name' => 'nullable|string|max:50',
                        'language' => Rule::requiredIf(fn() => request()->user()->hasRole('admin')),
                        'gender' => 'required|in:1,0',
                        'email' => [
                            Rule::requiredIf(!$isWriter),
                            'email',
                            'unique:users,email',
                            'min:3',
                            'max:30',
                        ],
                        'password' => [
                            Rule::requiredIf(!$isWriter),
                            'string',
                            'confirmed',
                            'min:8',
                            'max:50',
                        ],
                        'date_of_birth' => 'nullable|date|date_format:Y-m-d',
                        'phone' => [
                            Rule::requiredIf(!$isWriter),
                            'string',
                            'unique:users,phone',
                        ],
                        'is_notify' => 'required|boolean',
                        'image' => 'sometimes',
                        'x_link' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9._-]+$/|unique:users,x_link',
                        'linkedIn_link' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9._-]+$/|unique:users,linkedIn_link',
                        'status' => 'required|boolean',
                        //other data
                        'slug' => 'nullable|string|max:100|unique:users,slug|regex:/^[a-zA-Z0-9\s]+$/|unique:users,slug',
                        'wirters' => 'nullable|in:1,0',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisUser = $this->route('user');
                    return [

                        'first_name' => 'required|string|max:50|regex:/^[\p{L}\s\-]+$/u',
                        'last_name' => 'required|string|max:50|regex:/^[\p{L}\s\-]+$/u',
                        'user_name' => [
                            Rule::requiredIf(!$isWriter),
                            'string',
                            'regex:/^[a-zA-Z0-9_]+$/',
                            'max:255',
                            Rule::unique('users', 'user_name')->ignore($thisUser),
                        ],
                        'name' => 'nullable|string|max:50',
                        'role' => 'nullable|string|exists:roles,name',
                        'email' => [
                            Rule::requiredIf(!$isWriter),
                            'email',
                            'min:3',
                            'max:50',
                            Rule::unique('users', 'email')->ignore($thisUser),
                        ],
                        'password' => [
                            'nullable',
                            Rule::requiredIf(!$isWriter),
                            'string',
                            'confirmed',
                            'min:8',
                            'max:50',
                        ],
                        'status' => 'required|boolean',
                        'x_link' => [
                            'nullable',
                            'string',
                            'max:255',
                            'regex:/^[a-zA-Z0-9._-]+$/',
                            Rule::unique('users', 'x_link')->ignore($thisUser),
                        ],
                        'linkedIn_link' => [
                            'nullable',
                            'string',
                            'max:255',
                            'regex:/^[a-zA-Z0-9._-]+$/',
                            Rule::unique('users', 'linkedIn_link')->ignore($thisUser),
                        ],

                        'phone' => [
                            Rule::requiredIf(!$isWriter),
                            'string',
                            Rule::unique('users', 'phone')->ignore($thisUser),
                        ],
                        'date_of_birth' => 'nullable|date|date_format:Y-m-d|before:' . now()->subYears(10)->toDateString(),

                        //other data
                        'slug' => 'nullable|regex:/^[a-zA-Z0-9\s]+$/|string|max:100|unique:users,slug|unique:users,slug,' . $thisUser,
                        'wirters' => 'nullable|in:1,0',
                        'manager_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'work_location' => 'nullable|string|max:255',
                        'work_description' => 'nullable|string|max:1000',
                        'job_title' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',

                    ];
                }
            default:
                break;
        }
    }
}
