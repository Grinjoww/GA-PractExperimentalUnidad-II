<?php
use App\Security\Security;
/** @var string|null $error */
require __DIR__ . '/../partials/header.php';
?>
<div class="tarjeta tarjeta-auth">
    <h1>Iniciar sesión</h1>

    <?php if ($error): ?>
        <p class="alerta alerta-error"><?= Security::escape($error) ?></p>
    <?php endif; ?>

    <form method="post" action="/login.php" autocomplete="off">
        <?= Security::campoCsrf() ?>
        <label>Correo
            <input type="email" name="email" required>
        </label>
        <label>Contraseña
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn btn-primario">Entrar</button>
    </form>

    <p class="enlace-secundario">
        ¿No tienes cuenta? <a href="/registro.php">Regístrate</a>
    </p>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>
