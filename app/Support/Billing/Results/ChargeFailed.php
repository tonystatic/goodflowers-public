<?php

declare(strict_types=1);

namespace App\Support\Billing\Results;

use App\Support\Billing\Contracts\ChargeResult;
use App\Support\Billing\TransactionInfo;

class ChargeFailed extends BaseBillingResult implements ChargeResult
{
    public function __construct(TransactionInfo $transaction = null, $message = null)
    {
        parent::__construct(false, $transaction, $message);
    }
}
