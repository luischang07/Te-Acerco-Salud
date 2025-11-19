<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   * @param  string  $role  El rol requerido (admin, pharmacy, patient)
   */
  public function handle(Request $request, Closure $next, string $role): Response
  {
    // Verificar que el usuario esté autenticado
    if (!Auth::check()) {
      return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta sección.');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Verificar rol según el parámetro
    $hasRole = match ($role) {
      'admin' => $user->isAdmin(),
      'pharmacy' => $user->isPharmacyEmployee(),
      'patient' => $user->isPatient(),
      default => false,
    };

    if (!$hasRole) {
      abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    return $next($request);
  }
}
