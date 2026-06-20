<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use App\Security\Auth;

Auth::cerrarSesion();
header('Location: /login.php');
exit;
