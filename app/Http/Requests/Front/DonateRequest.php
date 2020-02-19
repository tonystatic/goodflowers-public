<?php

declare(strict_types=1);

namespace App\Http\Requests\Front;

class DonateRequest extends BaseRequest
{
    public function rules() : array
    {
        return [
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages() : array
    {
        return [
            'quantity.required' => 'Необходимо указать количество приобретаемых цветков.',
            'quantity.integer'  => 'Количество цветков должно быть целым числом.',
            'quantity.min'      => 'Можно приобрести не менее одного цветка.'
        ];
    }
}
