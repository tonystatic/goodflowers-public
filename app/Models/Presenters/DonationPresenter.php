<?php

declare(strict_types=1);

namespace App\Models\Presenters;

use App\Support\Money\Money;

trait DonationPresenter
{
    /**
     * @param int $value
     * @return \App\Support\Money\Money
     */
    public function getAmountAttribute(int $value) : Money
    {
        return Money::fromAmount($value);
    }
}
