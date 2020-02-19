<?php

declare(strict_types=1);

namespace App\Support\Billing\Results;

use App\Support\Billing\Contracts\ChargeResult;
use App\Support\Billing\TransactionInfo;

class Need3DSecure extends BaseBillingResult implements ChargeResult
{
    /** @var string */
    protected $url;

    /** @var array */
    protected $params;

    /** @var string */
    protected $callbackParamName;

    public function __construct(
        TransactionInfo $transaction,
        string $url,
        array $params,
        string $callbackParamName,
        string $message = null
    ) {
        parent::__construct(false, $transaction, $message);
        $this->url = $url;
        $this->params = $params;
        $this->callbackParamName = $callbackParamName;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @param string $callbackUrl
     * @return array
     */
    public function getParams(string $callbackUrl) : array
    {
        return \array_merge($this->params, [
            $this->callbackParamName => $callbackUrl
        ]);
    }
}
