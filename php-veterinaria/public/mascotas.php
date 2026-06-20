<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use App\Config\Database;
use App\Repositories\PdoMascotaRepository;
use App\Models\Mascota;
use App\Security\Security;
use App\Security\Auth;

// A01: ruta protegida — exige sesión iniciada.
Auth::requerirAutenticacion();

$repo   = new PdoMascotaRepository(Database::getConnection());
$accion = $_GET['accion'] ?? 'listar';
$error  = null;
$exito  = null;

// -------- Operaciones que mutan estado (POST + CSRF) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verificarCsrf($_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        exit('Token CSRF inválido.');
    }

    $op = $_POST['op'] ?? '';

    if ($op === 'crear' || $op === 'actualizar') {
        $mascota = new Mascota(
            id:          $op === 'actualizar' ? (int) $_POST['id'] : null,
            nombre:      trim($_POST['nombre'] ?? ''),
            especie:     trim($_POST['especie'] ?? ''),
            raza:        trim($_POST['raza'] ?? '') ?: null,
            edad:        ($_POST['edad'] ?? '') !== '' ? (int) $_POST['edad'] : null,
            nombreDueno: trim($_POST['nombre_dueno'] ?? ''),
            telefono:    trim($_POST['telefono'] ?? '') ?: null,
            creadoPor:   Auth::idUsuarioActual(),
        );

        if ($mascota->nombre === '' || $mascota->especie === '' || $mascota->nombreDueno === '') {
            $error = 'Nombre, especie y dueño son obligatorios.';
        } elseif ($op === 'crear') {
            $repo->crear($mascota);
            header('Location: /mascotas.php?exito=creada');
            exit;
        } else {
            $repo->actualizar($mascota);
            header('Location: /mascotas.php?exito=actualizada');
            exit;
        }
    }

    if ($op === 'eliminar') {
        $repo->eliminar((int) $_POST['id']);
        header('Location: /mascotas.php?exito=eliminada');
        exit;
    }
}

// -------- Mensajes de éxito vía query string --------
if (isset($_GET['exito'])) {
    $exito = match ($_GET['exito']) {
        'creada'       => 'Mascota registrada correctamente.',
        'actualizada'  => 'Mascota actualizada correctamente.',
        'eliminada'    => 'Mascota eliminada correctamente.',
        default        => null,
    };
}

// -------- Vistas --------
if ($accion === 'editar') {
    $mascota = $repo->buscarPorId((int) ($_GET['id'] ?? 0));
    if ($mascota === null) {
        header('Location: /mascotas.php');
        exit;
    }
    $titulo = 'Editar mascota';
    require __DIR__ . '/../templates/mascotas/form.php';
    exit;
}

if ($accion === 'nueva') {
    $mascota = null;
    $titulo  = 'Nueva mascota';
    require __DIR__ . '/../templates/mascotas/form.php';
    exit;
}

// Por defecto: listar
$mascotas = $repo->listarTodas();
$titulo   = 'Mascotas registradas';
require __DIR__ . '/../templates/mascotas/listar.php';
