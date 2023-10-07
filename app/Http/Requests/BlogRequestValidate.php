<?php

namespace App\Http\Requests;

use App\Models\Admin\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class BlogRequestValidate extends FormRequest
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
        // Retrieve the blog ID from the database based on the slug
        if (!empty($this->slug)) {
            $blog = Blog::where('slug', Crypt::decrypt($this->slug))->first();
        }
        // dd($blog, $this->isMethod('patch'), $this);
        $rules =  [
            'title' => 'required|max:255|unique:blogs,title,' . (!empty($blog) ? $blog->id : null),
            'category_id' => 'required',
            'tags' => 'required',
            'status' => 'required',
            'description' => 'required',
        ];
        if ($this->isMethod('patch')) {
            // Updating an existing blog, so image is optional
            // dd('first');
            $rules['image'] = 'nullable|mimes:png,jpg,jpeg';
        } else {
            // Creating a new blog, so image is required
            $rules['image'] = 'required|mimes:png,jpg,jpeg';
        }
        // dd($rules, $blog);
        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Blog Title is Required!',
            'title.unique' => 'Title Already Taken, Blog Title Must be Unique!',
            'category_id.required' => 'Title Already Taken, Blog Title Must be Unique!',
            'image.required' => 'Please Select Blog Image!',
            'tags.required' => 'Enter Some Tags for Blog!',
            'status.required' => 'Status Must be Checked!',
            'description.required' => 'Please Write Blog Details!',
        ];
    }
}
