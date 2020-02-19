<?php

declare(strict_types=1);

namespace App\Http\Transformers\Front;

use App\Services\Results\Billing\PaymentData;
use App\Support\Transformers\BaseTransformer;

class PaymentDataTransformer extends BaseTransformer
{
    /**
     * @param \App\Services\Results\Billing\PaymentData $paymentData
     * @return array
     */
    public function transform(PaymentData $paymentData) : array
    {
        return [
            'provider_id' => $paymentData->getProviderId(),
            'invoice_id'  => $paymentData->getInvoiceId(),
            'description' => $paymentData->getDescription(),
            'amount'      => $paymentData->getAmount(),
            'currency'    => \mb_strtoupper($paymentData->getCurrencyCode()),
            'email'       => $paymentData->getEmail(),
            'user_id'     => $paymentData->getUserId(),
            'data'        => $paymentData->getData()
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'payment_data';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'payment_data';
    }
}
