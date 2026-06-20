<?php

use App\Security\Security;
use App\Security\Auth;

/** @var string $titulo */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::escape($titulo ?? 'Veterinaria') ?></title>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
<header class="barra">
    <span class="logo">🐾 Clínica Veterinaria</span>
    <nav>
        <?php if (Auth::estaAutenticado()): ?>
            <span>Hola, <?= Security::escape(Auth::nombreUsuarioActual()) ?></span>
            <a href="/mascotas.php">Mascotas</a>
            <a href="/logout.php">Salir</a>
        <?php endif; ?>
    </nav>
</header>
<main class="contenedor">
