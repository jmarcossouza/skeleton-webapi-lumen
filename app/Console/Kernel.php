<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Isso é uma schedue pra limpar o log a cada determinado tempo. Podem ser adicionados outras schedues com outros tempos
        $schedule->call(function () {
            if (config('defaults.logs.excluir_antigos', false) === true) {
                $days = '-'.config('defaults.logs.dias_excluir_antigos', 360).' days';
                DB::table('logs')->where('data', '<', date('Y-m-d', strtotime($days)))->delete();
            }
        })->weekly(); //Run the task every sunday at 00:00

        // $schedule->call(function () {
        //     //Fazer algo...
        // })->cron('* * * * *');

                // $schedule->call(function () {
        //     //Fazer algo...
        // })->daily();

        //OBS: cuidado com o everyMinute(), porque, na verdade ele vai rodar sempre que o script for chamado, dado o fato que o laravel recomenda rodar o script a cada minuto.
    }
}
