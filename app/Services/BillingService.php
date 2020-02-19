<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Donation;
use App\Models\User;
use App\Repos\DonationRepo;
use App\Services\Results\Billing\InvoicePaid;
use App\Services\Results\Billing\PaymentData;
use App\Support\Billing\Contracts\BillingProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class BillingService
{
    /** @var \App\Support\Billing\Contracts\BillingProvider */
    protected $billingProvider;

    /**
     * BillingService constructor.
     */
    public function __construct()
    {
        $this->billingProvider = app(BillingProvider::class);
    }

    /**
     * @param \App\Models\Donation $donation
     * @return string
     */
    protected function buildDonationInvoiceId(Donation $donation) : string
    {
        return 'd_' . $donation->hash_id;
    }

    /**
     * @param \App\Models\User $user
     * @return string
     */
    protected function buildBillingUserId(User $user) : string
    {
        return 'user_' . $user->id;
    }

    /**
     * @param string $billingUserId
     * @return int|null
     */
    public function extractBillingUserId(string $billingUserId) : ?int
    {
        $result = \str_replace('user_', '', $billingUserId);
        if (\mb_strlen($result) === 0) {
            return null;
        }

        return (int) $result;
    }

    /**
     * @param \App\Models\Donation $donation
     * @return \App\Services\Results\Billing\PaymentData
     */
    public function getPaymentDataForDonation(Donation $donation) : PaymentData
    {
        return new PaymentData(
            $this->billingProvider->getPublicId(),
            $this->buildDonationInvoiceId($donation),
            'Оплата виртуального цветка',
            $donation->amount->getValue(),
            $donation->amount->getCurrencyCode(),
            $donation->email,
            $donation->user !== null ? $this->buildBillingUserId($donation->user) : null,
            [
                'transactableType' => $donation->getMorphClass(),
                'transactableId'   => $donation->id
            ]
        );
    }

    /**
     * @param \App\Models\Donation $donation
     * @return \App\Services\Results\Billing\InvoicePaid
     */
    public function donationPaid(Donation $donation) : InvoicePaid
    {
        return $this->invoicePaid($this->buildDonationInvoiceId($donation));
    }

    /**
     * @param string $invoiceId
     * @return \App\Services\Results\Billing\InvoicePaid
     */
    protected function invoicePaid(string $invoiceId) : InvoicePaid
    {
        $status = $this->billingProvider->invoiceStatus($invoiceId);
        if ($status->isUnsuccessful()) {
            return new InvoicePaid($status->getMessage());
        }

        return new InvoicePaid();
    }

    /**
     * @param string $type
     * @param int $transactableId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findTransactableFromTypeAndId(string $type, int $transactableId) : ?Model
    {
        switch (Relation::getMorphedModel($type)) {
            case Donation::class:
                /* @var \App\Repos\DonationRepo $donationRepo */
                $donationRepo = app(DonationRepo::class);

                return $donationRepo->findById($transactableId);
            default:
                return null;
        }
    }
}
