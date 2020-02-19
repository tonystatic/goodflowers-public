<?php

declare(strict_types=1);

namespace App\Support\Billing\Contracts;

use App\Support\Billing\TransactionInfo;

interface BillingResult
{
    /**
     * @return bool
     */
    public function isSuccessful() : bool;

    /**
     * @return bool
     */
    public function isUnsuccessful() : bool;

    /**
     * @return string|null
     */
    public function getMessage() : ?string;

    /**
     * @return bool
     */
    public function hasTransaction() : bool;

    /**
     * @return TransactionInfo|null
     */
    public function getTransaction() : ?TransactionInfo;
}
