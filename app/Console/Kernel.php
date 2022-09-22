<?php

namespace App\Console;

use App\Models\CloverOrder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
        $schedule
            ->command('email:deliveries')
            ->timezone('America/New_York')
            ->weeklyOn(1, '9:00');
        */

        $schedule
            ->command('model:prune', [
                '--model' => [CloverOrder::class],
            ])
            ->timezone('America/New_York')
            ->dailyAt('1:00');

        $schedule
            ->command('clover:import')
            ->timezone('America/New_York')
            ->dailyAt('6:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
