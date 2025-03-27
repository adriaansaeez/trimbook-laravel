<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EnviarWhatsApp implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $reserva = $this->reserva;
        $client = new \Twilio\Rest\Client(config('services.twilio.sid'), config('services.twilio.token'));
        $client->messages->create(
            "whatsapp:{$reserva->user->telefono}",
            [
                'from' => "whatsapp:".config('services.twilio.whatsapp_from'),
                'body' => "Tu reserva para {$reserva->servicio->nombre} está confirmada el {$reserva->fecha} a las {$reserva->hora}."
            ]
        );
    }

}
