<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\CustomeValidateExceptionHandler;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new CustomeValidateExceptionHandler($validator);
    }
}
