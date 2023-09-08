<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class storiesRequest extends FormRequest
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
            'author' => 'nullable|string',
            'genres' => 'required|json',
            'description' => 'nullable|string',
            'image_cover' => 'nullable|string',
            'image_future' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required'   => __('Tiêu đề không được bỏ trống'),
            'title.min'        => __('Tiêu đề quá ngắn'),
            'title.max'        => __('Tiêu đề không quá 255 ký tự'),
            'genres.required'  => __('Genres is required'),
            'genres.json'  => __('Genres must is json'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
