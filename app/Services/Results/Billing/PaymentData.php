<?php

declare(strict_types=1);

namespace App\Services\Results\Billing;

use App\Services\Results\BaseResult;

class PaymentData extends BaseResult
{
    /** @var string */
    protected $providerId;

    /** @var string */
    protected $invoiceId;

    /** @var string */
    protected $description;

    /** @var float */
    protected $amount;

    /** @var string */
    protected $currencyCode;

    /** @var string|null */
    protected $email;

    /** @var string|null */
    protected $userId;

    /** @var array */
    protected $data;

    public function __construct(
        string $providerId,
        string $invoiceId,
        string $description,
        float $amount,
        string $currencyCode,
        string $email = null,
        string $userId = null,
        array $data = []
    ) {
        parent::__construct();

        $this->providerId = $providerId;
        $this->invoiceId = $invoiceId;
        $this->description = $description;
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
        $this->email = $email;
        $this->userId = $userId;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getProviderId() : string
    {
        return $this->providerId;
    }

    /**
     * @return string
     */
    public function getInvoiceId() : string
    {
        return $this->invoiceId;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrencyCode() : string
    {
        return $this->currencyCode;
    }

    /**
     * @return string|null
     */
    public function getUserId() : ?string
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
}
