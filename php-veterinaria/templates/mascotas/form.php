<?php
use App\Security\Security;
use App\Models\Mascota;
/** @var Mascota|null $mascota */
/** @var string|null $error */
$editando = $mascota !== null;
require __DIR__ . '/../partials/header.php';
?>
<div class="tarjeta">
    <h1><?= $editando ? 'Editar mascota' : 'Nueva mascota' ?></h1>

    <?php if (!empty($error)): ?>
        <p class="alerta alerta-error"><?= Security::escape($error) ?></p>
    <?php endif; ?>

    <form method="post" action="/mascotas.php">
        <?= Security::campoCsrf() ?>
        <input type="hidden" name="op" value="<?= $editando ? 'actualizar' : 'crear' ?>">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= (int) $mascota->id ?>">
        <?php endif; ?>

        <label>Nombre *
            <input type="text" name="nombre" required maxlength="100"
                   value="<?= Security::escape($mascota->nombre ?? '') ?>">
        </label>
        <label>Especie *
            <input type="text" name="especie" required maxlength="50"
                   value="<?= Security::escape($mascota->especie ?? '') ?>">
        </label>
        <label>Raza
            <input type="text" name="raza" maxlength="80"
                   value="<?= Security::escape($mascota->raza ?? '') ?>">
        </label>
        <label>Edad (años)
            <input type="number" name="edad" min="0" max="120"
                   value="<?= $mascota?->edad !== null ? (int) $mascota->edad : '' ?>">
        </label>
        <label>Nombre del dueño *
            <input type="text" name="nombre_dueno" required maxlength="120"
                   value="<?= Security::escape($mascota->nombreDueno ?? '') ?>">
        </label>
        <label>Teléfono
            <input type="text" name="telefono" maxlength="20"
                   value="<?= Security::escape($mascota->telefono ?? '') ?>">
        </label>

        <div class="acciones-form">
            <button type="submit" class="btn btn-primario">
                <?= $editando ? 'Guardar cambios' : 'Registrar' ?>
            </button>
            <a href="/mascotas.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
