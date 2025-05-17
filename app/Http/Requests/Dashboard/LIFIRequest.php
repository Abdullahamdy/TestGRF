<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LIFIRequest extends FormRequest
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
                        'title' => 'required|string|max:255',
                        'source' => 'nullable|string|max:255',
                        'editor_id' => 'required',
                        'slug' => 'nullable',
                        'is_featured' => 'boolean',
                        'language' => Rule::requiredIf(fn() => request()->user()->hasRole('admin')),
                        'description' => 'nullable|string',
                        'main_image' => 'required',
                        'image_description' => 'nullable|string|max:255',
                        'is_published' => 'boolean|required_if:schudle_date,NULL|required_if:schudle_time,NULL',
                        'schudle_date' => 'nullable|date|after_or_equal:today|required_if:is_published,0',
                        'schudle_time' => 'nullable|date_format:H:i|required_if:is_published,0',
                        'meta_title' => 'nullable',
                        'meta_description' => 'nullable|string',
                        'meta_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                        'category_id' => 'nullable|integer|exists:categories,id',
                        'sub_category_id' => 'nullable|integer|exists:categories,id',
                        'tags'   => 'required|array',
                        'images' => 'array',
                        'tags.*' => 'required',
                        'direction' => 'required|in:ltr,rtl',
                        'file' => 'required|mimes:pdf',


                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    $thisNews = $this->route('news');
                    return [
                        'title' => 'required|string|max:255',
                        'source' => 'nullable|string|max:255',
                        'editor_id' => 'required',
                        'slug' => 'nullable',
                        'show_in_slider' => 'boolean',
                        'is_featured' => 'boolean',
                        'description' => 'nullable|string',
                        'main_image' => 'required_unless:news_type,video',
                        'image_description' => 'nullable|string|max:255',
                        'is_published' => 'boolean|required_if:schudle_date,NULL|required_if:schudle_time,NULL',
                        'schudle_date' => 'nullable|date|after_or_equal:today|required_if:is_published,0',
                        'schudle_time' => 'nullable|date_format:H:i|required_if:is_published,0',
                        'meta_title' => 'nullable|string|max:255',
                        'meta_description' => 'nullable|string',
                        'meta_image' => 'nullable',
                        'category_id' => 'required_if:news_type,==,normal|integer|exists:categories,id',
                        'sub_category_id' => 'nullable|integer|exists:categories,id',
                        'tags'   => 'required|array',
                        'tags.*' => 'required',
                        'direction' => 'required_if:news_type,==,special|in:ltr,rtl',
                        'file' => 'nullable',



                    ];
                }
        }
    }
}
