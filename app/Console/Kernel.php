<?php

namespace App\Console;

use App\Console\Commands\CheckMailIncommingCommand;
use App\Console\Commands\CheckMailOutgoingCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Webklex\IMAP\Commands\ImapIdleCommand;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ImapIdleCommand::class,
        CheckMailIncommingCommand::class,
        CheckMailOutgoingCommand::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
