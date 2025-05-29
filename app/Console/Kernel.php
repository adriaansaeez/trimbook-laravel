<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CreateTenant::class,
        \App\Console\Commands\EnviarRecordatoriosReservas::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Enviar recordatorios de reservas todos los días a las 10:00 AM
        $schedule->command('reservas:enviar-recordatorios')
                 ->dailyAt('10:00')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->emailOutputOnFailure('admin@trimbook.com')
                 ->appendOutputTo(storage_path('logs/recordatorios.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // Opcional: cargar comandos desde rutas de consola
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}

