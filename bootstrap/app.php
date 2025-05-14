<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__."/../routes/web.php",
        api: __DIR__."/../routes/api.php", // Verifique esta linha!
        commands: __DIR__."/../routes/console.php",
        health: "/up",
        apiPrefix: "api", // E esta linha para o prefixo "api"
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configuração de middleware aqui
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuração de exceções aqui
    })->create();

