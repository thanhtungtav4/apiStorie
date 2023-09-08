<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class authRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:6|max:30',
        ];
    }

    public function messages()
    {
        return [
            'email.required'   => __('Email không được bỏ trống'),
            'email.email'   => __('Email không đúng định dạng'),
            'password.min'        => __('Password quá ngắn'),
            'password.max'        => __('Password không quá 255 ký tự'),
        ];
    }
}
