<?php
use App\Security\Security;
/** @var string|null $error */
/** @var bool $ok */
require __DIR__ . '/../partials/header.php';
?>
<div class="tarjeta tarjeta-auth">
    <h1>Crear cuenta</h1>

    <?php if (!empty($ok)): ?>
        <p class="alerta alerta-ok">
            Cuenta creada. Ya puedes <a href="/login.php">iniciar sesión</a>.
        </p>
    <?php else: ?>
        <?php if ($error): ?>
            <p class="alerta alerta-error"><?= Security::escape($error) ?></p>
        <?php endif; ?>

        <form method="post" action="/registro.php" autocomplete="off">
            <?= Security::campoCsrf() ?>
            <label>Nombre
                <input type="text" name="nombre" required maxlength="100">
            </label>
            <label>Correo
                <input type="email" name="email" required maxlength="150">
            </label>
            <label>Contraseña (mín. 8 caracteres)
                <input type="password" name="password" required minlength="8">
            </label>
            <button type="submit" class="btn btn-primario">Registrarme</button>
        </form>

        <p class="enlace-secundario">
            ¿Ya tienes cuenta? <a href="/login.php">Inicia sesión</a>
        </p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
