<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CustomeValidateExceptionHandler extends ValidationException
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Validation Errors',
            'status' => false,
            'errors' => $this->validator->errors(),
        ], 422);
    }
}
