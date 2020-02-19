<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repos\DonationRepo;
use App\Services\DonationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DonationsClearIncomplete extends Command
{
    protected $signature = 'donations:clear-incomplete
        {--D|days=7 : Donation age in days}';

    protected $description = 'Remove old incomplete donations';

    /* @var \App\Repos\DonationRepo */
    protected $donationRepo;

    /* @var \App\Services\DonationService */
    protected $donationService;

    public function __construct()
    {
        parent::__construct();
        $this->donationRepo = app(DonationRepo::class);
        $this->donationService = app(DonationService::class);
    }

    public function handle() : void
    {
        $olderThanDays = (int) $this->option('days');
        $olderThanDays = $olderThanDays >= 0
            ? $olderThanDays
            : 0;

        $oldDonations = $this->donationRepo->getIncomplete(Carbon::now()->subDays($olderThanDays));
        if ($oldDonations->count() === 0) {
            $this->info('Незавершённые пожертвования не найдены.');

            return;
        }
        $bar = $this->output->createProgressBar($oldDonations->count());
        $bar->start();

        foreach ($oldDonations as $donation) {
            $this->donationService->deleteDonation($donation);
            $bar->advance();
        }

        $bar->finish();
        $this->output->newLine();
        $this->info('Незавершённые пожертвования успешно удалены.');
    }
}
