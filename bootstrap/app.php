<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
      'single.session' => \App\Http\Middleware\EnsureSingleSession::class,
      'role' => \App\Http\Middleware\CheckRole::class,
    ]);

    // Agregar middleware de logging de seguridad globalmente
    $middleware->append(\App\Http\Middleware\LogSecurityEvents::class);

    $middleware->web(append: [
      \App\Http\Middleware\SetLocale::class,
    ]);
  })
  ->withExceptions(function (): void {
    //
  })->create();
