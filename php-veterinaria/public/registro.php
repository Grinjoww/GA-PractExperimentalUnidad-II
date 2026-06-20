<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use App\Config\Database;
use App\Repositories\PdoUsuarioRepository;
use App\Controllers\AuthService;
use App\Security\Security;
use App\Security\Auth;

if (Auth::estaAutenticado()) {
    header('Location: /mascotas.php');
    exit;
}

$error = null;
$ok    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verificarCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Token CSRF inválido. Recargue la página.';
    } else {
        $service = new AuthService(new PdoUsuarioRepository(Database::getConnection()));
        $res = $service->registrar(
            $_POST['nombre']   ?? '',
            $_POST['email']    ?? '',
            $_POST['password'] ?? ''
        );
        if ($res['ok']) {
            $ok = true;
        } else {
            $error = $res['error'];
        }
    }
}

$titulo = 'Registro';
require __DIR__ . '/../templates/auth/registro.php';
