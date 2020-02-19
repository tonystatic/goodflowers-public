<?php

declare(strict_types=1);

namespace App\Http\Requests\Service;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends Request
{
    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) : void
    {
        throw new HttpResponseException(
            response()->json(['message' => 'Validation failed.'], 422)
        );
    }
}
