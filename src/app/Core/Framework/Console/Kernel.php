<?php

namespace App\Core\Framework\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('alarms:periodic-create')->monthlyOn(1, '0:00');
        $schedule->command('tasks:periodic-create')->monthlyOn(1, '0:00');
        $schedule->command('tasks:disable')->dailyAt('0:00');
        $schedule->command('notifications:insert')
            ->monthlyOn(25, '1:00');
        $schedule->command('notifications:send')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/../../../Task/Infrastructure/Command');
        $this->load(__DIR__ . '/../../../Alarm/Infrastructure/Command');
        require base_path('routes/console.php');
    }
}
