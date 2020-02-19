<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Donation;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Money\Money;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TransactionRepo
{
    /**
     * @param string $extId
     * @return \App\Models\User|null
     */
    public function findByExtId(string $extId) : ?Transaction
    {
        /* @var Transaction|null $transaction */
        $transaction = Transaction::query()
            ->where('ext_id', $extId)
            ->first();

        return $transaction;
    }

    /**
     * @param \App\Models\Donation $donation
     * @param \App\Models\User $user
     */
    public function setUserToAllByDonation(Donation $donation, User $user) : void
    {
        $donation->transactions()->update(['user_id' => $user->id]);
    }

    /**
     * @param bool $success
     * @param string $extId
     * @param \App\Support\Money\Money $amount
     * @param \Carbon\Carbon $madeAt
     * @param string $type
     * @param \App\Models\User|null $user
     * @param \Illuminate\Database\Eloquent\Model|null $transactable
     * @param string|null $cardType
     * @param int|null $cardDigits
     * @param int|null $cardExpMonth
     * @param int|null $cardExpYear
     * @param string|null $cardToken
     * @param string|null $cardholderName
     * @param string|null $cardholderEmail
     * @param string|null $errorCode
     * @return \App\Models\Transaction
     */
    public function create(
        bool $success,
        string $extId,
        Money $amount,
        Carbon $madeAt,
        string $type = Transaction::TYPE_PAYMENT,
        User $user = null,
        Model $transactable = null,
        string $cardType = null,
        int $cardDigits = null,
        int $cardExpMonth = null,
        int $cardExpYear = null,
        string $cardToken = null,
        string $cardholderName = null,
        string $cardholderEmail = null,
        string $errorCode = null
    ) : Transaction {
        $transaction = new Transaction();
        $transaction->fill([
            'ext_id'           => $extId,
            'successful'       => $success,
            'type'             => $type,
            'amount'           => $amount->getAmount(),
            'currency'         => $amount->getCurrencyCode(),
            'card_type'        => $cardType !== null ? \mb_strtolower($cardType) : null,
            'card_digits'      => $cardDigits,
            'card_exp_month'   => $cardExpMonth,
            'card_exp_year'    => $cardExpYear,
            'card_token'       => $cardToken,
            'cardholder_name'  => $cardholderName,
            'cardholder_email' => $cardholderEmail,
            'error_code'       => $errorCode,
            'made_at'          => $madeAt
        ]);
        if ($user !== null) {
            $transaction->user()->associate($user);
        }
        if ($transactable !== null) {
            $transaction->transactable()->associate($transactable);
        }
        $transaction->save();

        return $transaction;
    }
}
