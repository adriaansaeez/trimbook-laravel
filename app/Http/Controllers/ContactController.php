<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Swift_TransportException;
use Exception;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'descripcion' => 'required|string|max:1000',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only(['nombre', 'correo', 'descripcion']);
            
            // Log para debugging
            Log::info('Intentando enviar email de contacto', [
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'timestamp' => now()
            ]);

            // Verificar configuración de email antes de intentar enviar
            if (config('mail.default') === 'log') {
                Log::warning('Sistema configurado para usar driver de email LOG - no se enviará email real');
                return back()->with('success', '¡Mensaje recibido! (Modo de prueba: el email se guardó en logs del sistema)');
            }

            // Verificar configuración básica de mail
            if (empty(config('mail.from.address'))) {
                throw new Exception('Configuración de email FROM no establecida');
            }
            
            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->to('adriansaezbeltra@gmail.com')
                        ->subject('Nueva consulta desde TrimBook - ' . $data['nombre'])
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Email de contacto enviado exitosamente');
            return back()->with('success', '¡Gracias por tu interés! Te contactaremos pronto.');
            
        } catch (Swift_TransportException $e) {
            Log::error('Error de transporte de email: ' . $e->getMessage());
            return back()->with('error', 'Error de conexión SMTP: No se pudo conectar al servidor de email. Verifique la configuración de red.');
            
        } catch (\Illuminate\View\ViewException $e) {
            Log::error('Error en la vista de email: ' . $e->getMessage());
            return back()->with('error', 'Error en la plantilla de email: La vista de email no se pudo cargar correctamente.');
            
        } catch (\InvalidArgumentException $e) {
            Log::error('Error de configuración de email: ' . $e->getMessage());
            return back()->with('error', 'Error de configuración: Configuración de email inválida o incompleta.');
            
        } catch (Exception $e) {
            // Log completo del error para debugging
            Log::error('Error general al enviar email de contacto: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Configuración actual de mail: ', [
                'default' => config('mail.default'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);
            
            // Mensaje específico basado en el tipo de error
            $errorMessage = 'Error desconocido al enviar el mensaje.';
            
            if (strpos($e->getMessage(), 'Connection') !== false) {
                $errorMessage = 'Error de conexión: No se pudo conectar al servidor de email.';
            } elseif (strpos($e->getMessage(), 'Authentication') !== false) {
                $errorMessage = 'Error de autenticación: Credenciales de email incorrectas.';
            } elseif (strpos($e->getMessage(), 'view') !== false || strpos($e->getMessage(), 'View') !== false) {
                $errorMessage = 'Error en la plantilla: No se pudo cargar la vista de email.';
            } elseif (strpos($e->getMessage(), 'configuration') !== false || strpos($e->getMessage(), 'config') !== false) {
                $errorMessage = 'Error de configuración: Configuración de email incompleta.';
            }
            
            return back()->with('error', $errorMessage . ' Error técnico: ' . $e->getMessage());
        }
    }
} 