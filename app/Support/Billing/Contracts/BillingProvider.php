<?php

declare(strict_types=1);

namespace App\Support\Billing\Contracts;

use App\Support\Billing\Results\InvoiceStatus;

interface BillingProvider
{
    /**
     * @return string
     */
    public function getPublicId() : string;

    /**
     * @param string $cryptogram
     * @param float $amount
     * @param string $currency
     * @param string $ip
     * @param bool $requireConfirmation
     * @param string|null $cardholderName
     * @param string|null $userId
     * @param string|null $description
     * @param array $extraData
     * @return \App\Support\Billing\Contracts\ChargeResult
     */
    public function chargeByCryptogram(
        string $cryptogram,
        float $amount,
        string $currency,
        string $ip,
        bool $requireConfirmation = false,
        string $cardholderName = null,
        string $userId = null,
        string $description = null,
        array $extraData = []
    ) : ChargeResult;

    /**
     * @param int $transactionId
     * @return bool
     */
    public function voidPayment(int $transactionId) : bool;

    /**
     * @param int $transactionId
     * @param string $paRes
     * @return \App\Support\Billing\Contracts\ChargeResult
     */
    public function confirm3DSecure(int $transactionId, string $paRes) : ChargeResult;

    /**
     * @param string $invoiceId
     * @return \App\Support\Billing\Results\InvoiceStatus
     */
    public function invoiceStatus(string $invoiceId) : InvoiceStatus;
}
