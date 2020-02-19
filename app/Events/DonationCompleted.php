<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Donation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /* @var \App\Models\Donation */
    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * @return \App\Models\Donation
     */
    public function getDonation() : Donation
    {
        return $this->donation;
    }
}
