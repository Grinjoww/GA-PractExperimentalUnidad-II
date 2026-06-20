<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use App\Security\Auth;

if (Auth::estaAutenticado()) {
    header('Location: /mascotas.php');
} else {
    header('Location: /login.php');
}
exit;
