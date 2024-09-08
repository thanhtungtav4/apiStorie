<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class StoriesRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'slug' => 'nullable|string',
            'title' => 'required|min:2|max:255',
            'author_id' => 'nullable|string',
            'genres' => 'required|json',
            'tags' => 'required|json',
            'description' => 'nullable|string',
            'image_cover' => 'nullable|string',
            'image_feature' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }

}
