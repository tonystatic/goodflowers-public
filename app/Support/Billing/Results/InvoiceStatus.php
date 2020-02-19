<?php

declare(strict_types=1);

namespace App\Support\Billing\Results;

use App\Support\Billing\Contracts\BillingResult;

class InvoiceStatus extends BaseBillingResult implements BillingResult
{
    public function __construct(bool $success = true, $message = null)
    {
        parent::__construct($success, null, $message);
    }
}
