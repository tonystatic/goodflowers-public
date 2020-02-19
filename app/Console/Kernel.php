<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\DonationsClearIncomplete;
use App\Console\Commands\FlowersOptimize;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule) : void
    {
        $schedule->command(FlowersOptimize::class, ['--force'])
            ->everyTenMinutes();
        $schedule->command(DonationsClearIncomplete::class)
            ->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands() : void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
