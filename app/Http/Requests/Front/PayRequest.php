<?php

declare(strict_types=1);

namespace App\Http\Requests\Front;

class PayRequest extends BaseRequest
{
    public function rules() : array
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function messages() : array
    {
        return [
            'email.required' => 'Необходимо указать email для получения чека.',
            'email.email'    => 'Неверный формат email-а.'
        ];
    }
}
