<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use  App\Console\Commands\cronValidadePrazoSusep;
use  App\Console\Commands\cronValidadePrazoEmbarqueProposta;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [ //
        cronValidadePrazoSusep::class,
        cronValidadePrazoEmbarqueProposta::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notify:prazo_susep')->dailyAt('06:00');
        $schedule->command('notify:prazo_embarque')->sendOutputTo('teste.txt')
            ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
