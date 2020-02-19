<?php

declare(strict_types=1);

namespace App\Http\Controllers\Service;

use App\Http\Requests\Service\Billing\FailedRequest;
use App\Http\Requests\Service\Billing\PaidRequest;
use App\Models\Donation;
use App\Models\Transaction;
use App\Repos\TransactionRepo;
use App\Repos\UserRepo;
use App\Services\BillingService;
use App\Services\DonationService;
use App\Support\Money\Money;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillingController extends BaseController
{
    /**
     * @param \App\Http\Requests\Service\Billing\PaidRequest $request
     * @return JsonResponse
     */
    public function paid(PaidRequest $request) : JsonResponse
    {
        $this->createTransactionIfDoesntExist($request, true, Transaction::TYPE_PAYMENT);

        return response()->json([
            'code' => 0
        ]);
    }

    /**
     * @param FailedRequest $request
     * @return JsonResponse
     */
    public function failed(FailedRequest $request) : JsonResponse
    {
        $type = Transaction::TYPE_PAYMENT;
        switch ($request->input('OperationType')) {
            case 'Refund':
                $type = Transaction::TYPE_REFUND;
                break;
        }
        $this->createTransactionIfDoesntExist($request, false, $type);

        return response()->json([
            'code' => 0
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param bool $success
     * @param string $type
     * @return \App\Models\Transaction
     */
    protected function createTransactionIfDoesntExist(Request $request, bool $success, string $type) : Transaction
    {
        /** @var \App\Repos\TransactionRepo $transactionRepo */
        $transactionRepo = app(TransactionRepo::class);

        $extId = (string) $request->input('TransactionId');

        $transaction = $transactionRepo->findByExtId($extId);

        if ($transaction === null) {
            $amount = Money::fromValue(
                (float) $request->input('Amount')
            );
            $cardExpMonth = $request->has('CardExpDate')
                ? $this->extractExpMonth((string) $request->input('CardExpDate'))
                : null;
            $cardExpYear = $request->has('CardExpDate')
                ? $this->extractExpYear((string) $request->input('CardExpDate'))
                : null;

            $transactable = $this->extractTransactableFromDataInput(
                $request->input('Data')
            );

            if (
                $transactable instanceof Donation
                && $transactable->user !== null
            ) {
                $user = $transactable->user;
            } else {
                /** @var \App\Services\BillingService $billingService */
                $billingService = app(BillingService::class);
                /** @var \App\Repos\UserRepo $userRepo */
                $userRepo = app(UserRepo::class);

                $userId = $request->has('AccountId')
                    ? $billingService->extractBillingUserId((string) $request->input('AccountId'))
                    : null;
                $user = $userId !== null ? $userRepo->findById($userId) : null;
            }

            /** @var \App\Services\DonationService $donationService */
            $donationService = app(DonationService::class);
            if (
                $success
                && $transactable instanceof Donation
                && ! $transactable->complete
            ) {
                $donationService->completeDonation($transactable);
            }

            $transaction = $transactionRepo->create(
                $success,
                $extId,
                $amount,
                Carbon::createFromFormat('Y-m-d H:i:s', $request->input('DateTime')),
                $type,
                $user,
                $transactable,
                $request->has('CardType') ? (string) $request->input('CardType') : null,
                $request->has('CardLastFour') ? (int) $request->input('CardLastFour') : null,
                $cardExpMonth,
                $cardExpYear,
                $request->has('Token') ? (string) $request->input('Token') : null,
                $request->has('Name') ? (string) $request->input('Name') : null,
                $request->has('Email') ? (string) $request->input('Email') : null,
                $request->has('ReasonCode') ? (string) $request->input('ReasonCode') : null
            );
        }

        return $transaction;
    }

    /**
     * @param string $expDate
     * @return int
     */
    protected function extractExpMonth(string $expDate) : int
    {
        return (int) \explode('/', $expDate)[0];
    }

    /**
     * @param string $expDate
     * @return int
     */
    protected function extractExpYear(string $expDate) : int
    {
        return (int) \explode('/', $expDate)[1];
    }

    /**
     * @param string|null $dataInput
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function extractTransactableFromDataInput(?string $dataInput) : ?Model
    {
        $transactable = null;
        if ($dataInput !== null) {
            $data = [];
            try {
                $data = (array) \json_decode($dataInput, true);
            } catch (Exception $e) {
            }
            if (
                isset($data['transactableId'], $data['transactableType'])
                && \is_string($data['transactableType'])
                && (\is_int($data['transactableId']) || \is_string($data['transactableId']))
            ) {
                /* @var BillingService $billingService */
                $billingService = app(BillingService::class);
                $transactable = $billingService->findTransactableFromTypeAndId(
                    $data['transactableType'],
                    (int) $data['transactableId']
                );
            }
        }

        return $transactable;
    }
}
