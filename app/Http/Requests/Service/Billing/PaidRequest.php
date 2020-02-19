<?php

declare(strict_types=1);

namespace App\Http\Requests\Service\Billing;

use App\Exceptions\Auth\Billing\NotificationFailedValidationException;
use App\Http\Requests\Service\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaidRequest extends BaseRequest
{
    public function rules() : array
    {
        return [
            'TransactionId' => 'required',
            'Amount'        => 'required',
            'Currency'      => 'required',
            'DateTime'      => 'required|date_format:Y-m-d H:i:s',
            'Data'          => 'nullable|json'
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        error_notification()->notifyException(new NotificationFailedValidationException(), [
            'input'  => $this->all(),
            'failed' => $validator->failed()
        ]);

        throw new HttpResponseException(
            response()->json(['code' => 0])
        );
    }
}
