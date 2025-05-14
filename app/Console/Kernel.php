<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CreateTenant::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Define tareas programadas aquí si las necesitas
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

