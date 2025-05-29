<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Mail\RecordatorioReserva;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnviarRecordatoriosReservas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservas:enviar-recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios por email 24 horas antes de las reservas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Buscando reservas que necesiten recordatorio...');

        // Calcular la fecha de mañana
        $manana = Carbon::tomorrow();
        
        // Buscar reservas para mañana que no hayan sido notificadas
        $reservas = Reserva::with(['user.perfil', 'estilista.user.perfil', 'servicio'])
            ->whereDate('fecha', $manana)
            ->where('recordatorio_enviado', false)
            ->whereIn('estado', ['CONFIRMADA', 'PENDIENTE'])
            ->get();

        if ($reservas->isEmpty()) {
            $this->info('✅ No hay reservas que necesiten recordatorio para mañana.');
            return 0;
        }

        $this->info("📧 Encontradas {$reservas->count()} reservas para enviar recordatorio.");

        $enviadosExitosos = 0;
        $errores = 0;

        foreach ($reservas as $reserva) {
            try {
                // Verificar que el usuario tenga email
                if (!$reserva->user->email) {
                    $this->warn("⚠️ Reserva ID {$reserva->id}: Usuario sin email");
                    continue;
                }

                // Enviar el email
                Mail::to($reserva->user->email)->send(new RecordatorioReserva($reserva));

                // Marcar como enviado
                $reserva->update([
                    'recordatorio_enviado' => true,
                    'recordatorio_enviado_at' => now()
                ]);

                $clienteNombre = $reserva->user->perfil->nombre ?? $reserva->user->name;
                $fecha = Carbon::parse($reserva->fecha)->format('d/m/Y');
                $hora = Carbon::parse($reserva->hora)->format('H:i');
                
                $this->info("✅ Recordatorio enviado a {$clienteNombre} para el {$fecha} a las {$hora}");
                
                $enviadosExitosos++;

                // Pequeña pausa para evitar saturar el servidor de email
                sleep(1);

            } catch (\Exception $e) {
                $this->error("❌ Error enviando recordatorio para reserva ID {$reserva->id}: " . $e->getMessage());
                $errores++;
            }
        }

        // Resumen final
        $this->newLine();
        $this->info("📊 Resumen de envío de recordatorios:");
        $this->info("✅ Enviados exitosamente: {$enviadosExitosos}");
        
        if ($errores > 0) {
            $this->error("❌ Errores: {$errores}");
        }

        $this->info("🎉 Proceso completado!");

        return 0;
    }
}
