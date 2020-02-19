<?php

declare(strict_types=1);

namespace App\Support\Billing;

use App\Support\Money\Money;
use Carbon\Carbon;

class TransactionInfo
{
    /** @var int */
    protected $id;

    /** @var string|null */
    protected $token = null;

    /** @var string|null */
    protected $cardType = null;

    /** @var string|null */
    protected $cardDigits = null;

    /** @var int|null */
    protected $cardExpMonth = null;

    /** @var int|null */
    protected $cardExpYear = null;

    /** @var string|null */
    protected $cardHolderName = null;

    /** @var string|null */
    protected $email = null;

    /** @var Money|null */
    protected $payment = null;

    /** @var Carbon|null */
    protected $date = null;

    /** @var string|null */
    protected $description = null;

    /** @var string|null */
    protected $reason = null;

    /** @var string|null */
    protected $reasonCode = null;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getToken() : ?string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getCardType() : ?string
    {
        return $this->cardType;
    }

    /**
     * @return string/null
     */
    public function getCardDigits() : ?string
    {
        return $this->cardDigits;
    }

    /**
     * @return int|null
     */
    public function getCardExpMonth() : ?int
    {
        return $this->cardExpMonth;
    }

    /**
     * @return int|null
     */
    public function getCardExpYear() : ?int
    {
        return $this->cardExpYear;
    }

    /**
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $token
     * @return TransactionInfo
     */
    public function setToken(?string $token) : self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param string|null $email
     * @return TransactionInfo
     */
    public function setEmail(?string $email) : self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string|null $type
     * @param string|null $digits
     * @param int|null $expMonth
     * @param int|null $expYear
     * @param string|null $cardHolderName
     * @return TransactionInfo
     */
    public function setCardInfo(
        string $type = null,
        string $digits = null,
        int $expMonth = null,
        int $expYear = null,
        string $cardHolderName = null
    ) : self {
        $this->cardType = $type;
        $this->cardDigits = $digits;
        $this->cardExpMonth = $expMonth;
        $this->cardExpYear = $expYear;
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    /**
     * @param float $amount
     * @param string $currency
     * @return TransactionInfo
     */
    public function setPayment(float $amount, string $currency) : self
    {
        $this->payment = Money::fromValue($amount);

        return $this;
    }

    /**
     * @return Money|null
     */
    public function getPayment() : ?Money
    {
        return $this->payment;
    }

    /**
     * @return Carbon|null
     */
    public function getDate() : ?Carbon
    {
        return $this->date;
    }

    /**
     * @param Carbon|null $date
     * @return TransactionInfo
     */
    public function setDate(?Carbon $date) : self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCardHolderName() : ?string
    {
        return $this->cardHolderName;
    }

    /**
     * @return string|null
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return TransactionInfo
     */
    public function setDescription(?string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason() : ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     * @param string|null $reasonCode
     * @return TransactionInfo
     */
    public function setReason(?string $reason, ?string $reasonCode) : self
    {
        $this->reason = $reason;
        $this->reasonCode = $reasonCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReasonCode() : ?string
    {
        return $this->reasonCode;
    }
}
