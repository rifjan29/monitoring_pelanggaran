<?php

namespace App\Console;

use App\Console\Commands\GenerateLaporanTask;
use App\Console\Commands\SendLaporanTask;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected $commands = [
        GenerateLaporanTask::class,
        SendLaporanTask::class,
    ];
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:generate-laporan-task')->weekly()->appendOutputTo(storage_path('logs/inspire.log'));
        $schedule->command('app:send-laporan-task')->daily()->appendOutputTo(storage_path('logs/inspire.log'));

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
