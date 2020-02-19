<?php

declare(strict_types=1);

namespace App\Jobs\Billing;

use App\Support\Billing\Contracts\BillingProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VoidPayment implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    protected $transactionId;

    /** @var \App\Support\Billing\Contracts\BillingProvider */
    protected $billingProvider;

    /**
     * VoidPayment constructor.
     * @param int $transactionId
     */
    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
        $this->billingProvider = app(BillingProvider::class);
    }

    public function handle() : void
    {
        $this->billingProvider->voidPayment($this->transactionId);
    }
}
