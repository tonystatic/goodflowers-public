<?php

declare(strict_types=1);

namespace App\Support\Billing\Providers;

use App\Support\Billing\Contracts\BillingProvider;
use App\Support\Billing\Contracts\ChargeResult;
use App\Support\Billing\Results\ChargeFailed;
use App\Support\Billing\Results\ChargeSucceeded;
use App\Support\Billing\Results\InvoiceStatus;
use App\Support\Billing\Results\Need3DSecure;
use App\Support\Billing\TransactionInfo;
use Carbon\Carbon;
use CloudPayments\Exception\PaymentException;
use CloudPayments\Manager;
use CloudPayments\Model\Required3DS;
use CloudPayments\Model\Transaction;
use DateTime;
use Exception;

class CloudPayments implements BillingProvider
{
    /** @var Manager */
    protected $cloudPayments;

    public function __construct()
    {
        $this->cloudPayments = new Manager(
            config('services.cloudpayments.public'),
            config('services.cloudpayments.private')
        );
        $this->cloudPayments->setLocale('ru-RU');
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicId() : string
    {
        return $this->cloudPayments->getPublicKey();
    }

    /**
     * {@inheritdoc}
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
    ) : ChargeResult {
        $params = [];
        if ($userId) {
            $params['AccountId'] = $userId;
        }
        if ($description) {
            $params['Description'] = $description;
        }
        if (\count($extraData) > 0) {
            $params['JsonData'] = \json_encode($extraData);
        }

        try {
            $result = $this->cloudPayments->chargeCard(
                $amount,
                $currency,
                $ip,
                $cardholderName,
                $cryptogram,
                $params,
                $requireConfirmation
            );
        } catch (PaymentException $e) {
            error_notification()->notifyException($e, \compact([
                'amount', 'currency', 'ip', 'cardholderName', 'cryptogram', 'params', 'requireConfirmation'
            ]));

            return new ChargeFailed(null, $e->getCardHolderMessage());
        } catch (Exception $e) { // also RequestException
            error_notification()->notifyException($e, \compact([
                'amount', 'currency', 'ip', 'cardholderName', 'cryptogram', 'params', 'requireConfirmation'
            ]));

            return new ChargeFailed();
        }
        $transactionInfo = $this->extractTransactionInfo($result);

        if ($result instanceof Required3DS) {
            return new Need3DSecure($transactionInfo, (string) $result->getUrl(), [
                'PaReq' => $result->getToken(),
                'MD'    => $result->getTransactionId()
            ], 'TermUrl');
        }

        return new ChargeSucceeded($transactionInfo);
    }

    /**
     * {@inheritdoc}
     */
    public function voidPayment(int $transactionId) : bool
    {
        try {
            $this->cloudPayments->voidPayment($transactionId);
        } catch (Exception $e) { // RequestException
            error_notification()->notifyException($e, \compact([
                'transactionId'
            ]));

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function confirm3DSecure(int $transactionId, string $paRes) : ChargeResult
    {
        try {
            $result = $this->cloudPayments->confirm3DS($transactionId, $paRes);
        } catch (PaymentException $e) {
            error_notification()->notifyException($e, \compact([
                'transactionId', 'paRes'
            ]));

            return new ChargeFailed(null, $e->getCardHolderMessage());
        } catch (Exception $e) { // also RequestException
            error_notification()->notifyException($e, \compact([
                'transactionId', 'paRes'
            ]));

            return new ChargeFailed();
        }
        $transactionInfo = $this->extractTransactionInfo($result);

        return new ChargeSucceeded($transactionInfo);
    }

    /**
     * {@inheritdoc}
     */
    public function invoiceStatus(string $invoiceId) : InvoiceStatus
    {
        try {
            $this->cloudPayments->findPayment($invoiceId);
        } catch (Exception $e) { // RequestException
            error_notification()->notifyException($e, \compact([
                'invoiceId'
            ]));

            return new InvoiceStatus(false, $e->getMessage());
        }

        return new InvoiceStatus(true);
    }

    /**
     * @param Required3DS|Transaction $result
     * @return TransactionInfo|null
     */
    protected function extractTransactionInfo($result) : ?TransactionInfo
    {
        $transactionInfo = null;
        if ($result instanceof Required3DS) {
            $transactionInfo = new TransactionInfo($result->getTransactionId());
        } elseif ($result instanceof Transaction) {
            $transactionInfo = (new TransactionInfo($result->getId()))
                ->setToken($result->getToken() !== null ? (string) $result->getToken() : null)
                ->setCardInfo(
                    $result->getCardType() !== null ? (string) $result->getCardType() : null,
                    $result->getCardLastFour() !== null ? (string) $result->getCardLastFour() : null,
                    $result->getCardExpiredMonth() !== null ? (int) $result->getCardExpiredMonth() : null,
                    $result->getCardExpiredYear() !== null ? (int) $result->getCardExpiredYear() : null,
                    $result->getCardHolderName() !== null ? (string) $result->getCardHolderName() : null
                )
                ->setEmail($result->getEmail() !== null ? (string) $result->getEmail() : null)
                ->setPayment(
                    (float) $result->getAmount(),
                    (string) $result->getCurrency()
                )
                ->setDate(
                    $result->getCreatedAt() instanceof DateTime
                        ? Carbon::instance($result->getCreatedAt())
                        : null
                )
                ->setDescription(
                    $result->getDescription() !== null ? (string) $result->getDescription() : null
                )
                ->setReason(
                    $result->getReason() !== null ? (string) $result->getReason() : null,
                    $result->getReasonCode() !== null ? (string) $result->getReasonCode() : null
                );
        }

        return $transactionInfo;
    }
}
