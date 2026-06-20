<?php
declare(strict_types=1);

/**
 * Bootstrap de la aplicación.
 * Si Composer está disponible usa su autoload; si no, registra
 * un autoloader PSR-4 manual (para entornos sin `composer install`).
 */

$composer = __DIR__ . '/../vendor/autoload.php';

if (file_exists($composer)) {
    require $composer;
} else {
    spl_autoload_register(function (string $clase): void {
        $prefijo = 'App\\';
        if (!str_starts_with($clase, $prefijo)) {
            return;
        }
        $ruta = __DIR__ . '/../src/'
              . str_replace('\\', '/', substr($clase, strlen($prefijo)))
              . '.php';
        if (file_exists($ruta)) {
            require $ruta;
        }
    });
}

use App\Security\Security;

Security::iniciarSesionSegura();
Security::cabecerasSeguridad();
