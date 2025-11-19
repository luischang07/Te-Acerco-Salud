<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSecurityEvents
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    // Loggear acceso a rutas administrativas
    if ($request->is('admin/*')) {
      Log::channel('security')->info('Admin access', [
        'user_id' => $user?->user_id,
        'user_email' => $user?->correo,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'route' => $request->path(),
        'method' => $request->method(),
        'status' => $response->getStatusCode(),
        'timestamp' => now()->toDateTimeString(),
      ]);
    }

    // Loggear acceso a rutas de farmacia
    if ($request->is('pharmacy/*')) {
      Log::channel('security')->info('Pharmacy access', [
        'user_id' => $user?->user_id,
        'user_email' => $user?->correo,
        'employee_branch' => $user?->empleado?->sucursal_id,
        'ip' => $request->ip(),
        'route' => $request->path(),
        'method' => $request->method(),
        'status' => $response->getStatusCode(),
        'timestamp' => now()->toDateTimeString(),
      ]);
    }

    // Loggear respuestas 403 (acceso denegado)
    if ($response->getStatusCode() === 403) {
      Log::channel('security')->warning('Access denied (403)', [
        'user_id' => $user?->user_id,
        'user_email' => $user?->correo,
        'ip' => $request->ip(),
        'route' => $request->path(),
        'method' => $request->method(),
        'timestamp' => now()->toDateTimeString(),
      ]);
    }

    return $response;
  }
}
