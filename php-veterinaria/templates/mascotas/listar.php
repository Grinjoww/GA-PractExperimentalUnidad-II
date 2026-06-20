<?php
use App\Security\Security;
use App\Models\Mascota;
/** @var Mascota[] $mascotas */
/** @var string|null $exito */
require __DIR__ . '/../partials/header.php';
?>
<div class="cabecera-seccion">
    <h1>Mascotas registradas</h1>
    <a href="/mascotas.php?accion=nueva" class="btn btn-primario">+ Nueva mascota</a>
</div>

<?php if (!empty($exito)): ?>
    <p class="alerta alerta-ok"><?= Security::escape($exito) ?></p>
<?php endif; ?>

<?php if (count($mascotas) === 0): ?>
    <p class="vacio">Aún no hay mascotas registradas.</p>
<?php else: ?>
<table class="tabla">
    <thead>
        <tr>
            <th>Nombre</th><th>Especie</th><th>Raza</th>
            <th>Edad</th><th>Dueño</th><th>Teléfono</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($mascotas as $m): ?>
        <tr>
            <td><?= Security::escape($m->nombre) ?></td>
            <td><?= Security::escape($m->especie) ?></td>
            <td><?= Security::escape($m->raza ?? '—') ?></td>
            <td><?= $m->edad !== null ? (int) $m->edad : '—' ?></td>
            <td><?= Security::escape($m->nombreDueno) ?></td>
            <td><?= Security::escape($m->telefono ?? '—') ?></td>
            <td class="acciones">
                <a href="/mascotas.php?accion=editar&id=<?= (int) $m->id ?>"
                   class="btn btn-mini">Editar</a>
                <form method="post" action="/mascotas.php" class="inline"
                      onsubmit="return confirm('¿Eliminar a <?= Security::escape($m->nombre) ?>?');">
                    <?= Security::campoCsrf() ?>
                    <input type="hidden" name="op" value="eliminar">
                    <input type="hidden" name="id" value="<?= (int) $m->id ?>">
                    <button type="submit" class="btn btn-mini btn-peligro">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php require __DIR__ . '/../partials/footer.php'; ?>
