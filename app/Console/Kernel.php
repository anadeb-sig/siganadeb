<?php

namespace App\Console;

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
        $schedule->command('update:etat_contrat')->daily();
        $schedule->command('update:etat_ouvrage')->daily();

        $schedule->command('update:paiements')
        ->dailyAt('00:00')
        ->then(function () {
            Artisan::call('insert:etatpaiements');
        })
        ->then(function () {
            Artisan::call('insert:ptf_villages');
        })
        ->then(function () {
            Artisan::call('insert:etat_ptf_village');
        })
        ->then(function () {
            Artisan::call('insert:etat_par_beneficiaires');
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
