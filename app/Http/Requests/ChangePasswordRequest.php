<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            'password' => 'required|string',
            'repassword' => 'required|same:password|string',
        ];
    }
}
