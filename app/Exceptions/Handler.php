<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Solo en producción y para errores importantes
            if (app()->environment('production') && $this->shouldReport($e)) {
                $this->sendErrorEmail($e);
            }
        });
    }

    /**
     * Enviar email con detalles del error
     */
    private function sendErrorEmail(Throwable $e): void
    {
        try {
            $errorData = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'timestamp' => now()->toDateTimeString(),
                'environment' => app()->environment(),
            ];

            Mail::raw($this->formatErrorEmail($errorData), function ($message) {
                $message->to('victorperezveliz1998@gmail.com')
                        ->subject('[UNFV Attendance] Error en Producción - ' . now()->format('Y-m-d H:i:s'));
            });
        } catch (\Exception $mailException) {
            // Si falla el envío de email, al menos log el error original
            Log::error('Failed to send error email: ' . $mailException->getMessage());
            Log::error('Original error: ' . $e->getMessage());
        }
    }

    /**
     * Formatear email de error
     */
    private function formatErrorEmail(array $errorData): string
    {
        return "
🚨 ERROR EN UNFV ATTENDANCE 🚨

Timestamp: {$errorData['timestamp']}
Environment: {$errorData['environment']}

📍 ERROR DETAILS:
Message: {$errorData['message']}
File: {$errorData['file']}
Line: {$errorData['line']}

🌐 REQUEST INFO:
URL: {$errorData['url']}
User Agent: {$errorData['user_agent']}
IP Address: {$errorData['ip']}

📊 STACK TRACE:
{$errorData['trace']}

---
Este email fue enviado automáticamente desde el sistema UNFV Attendance.
        ";
    }
}