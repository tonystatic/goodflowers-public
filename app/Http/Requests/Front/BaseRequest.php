<?php

declare(strict_types=1);

namespace App\Http\Requests\Front;

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
        $plainErrorsArray = [];
        foreach ($validator->errors()->getMessages() as $errorGroup) {
            if (\is_array($errorGroup)) {
                $plainErrorsArray = \array_merge($plainErrorsArray, $errorGroup);
            }
        }

        throw new HttpResponseException(
            standard_response()->errors($plainErrorsArray, null, [], false)
        );
    }
}
