<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;


class chapterRequest extends FormRequest
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
            'storie_id' => 'required|numeric',
            'title' => 'required|min:2|max:255',
            'order' => 'nullable|numeric',
            'content' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required'   => __('Tiêu đề không được bỏ trống'),
            'title.min'        => __('Tiêu đề quá ngắn'),
            'title.max'        => __('Tiêu đề không quá 255 ký tự'),
            'storie_id.required'        => __('ID story không được để trống'),
            'storie_id.numeric'        => __('ID phải là kiểu số'),
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
