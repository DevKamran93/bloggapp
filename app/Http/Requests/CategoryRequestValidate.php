<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequestValidate extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'type' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Category Title is Required!',
            'type.required' => 'Please Select Category Type!',
        ];
    }
}
