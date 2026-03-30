<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | Pulse Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="auth-page">
    <?php $errors = session('errors') ?? []; ?>
    <main class="auth-shell">
        <section class="auth-panel auth-panel-copy">
            <p class="eyebrow">Proyecto nuevo</p>
            <h1>Misma idea, base mucho mas sana.</h1>
            <p class="lede">Este arranque deja atras el legado viejo y te da una estructura de CI4 que puedes convertir luego en salas, mensajes privados o websockets.</p>
        </section>

        <section class="auth-panel auth-panel-form">
            <div class="auth-card">
                <p class="card-kicker">Primer paso</p>
                <h2>Crear cuenta</h2>

                <?php if (session('error')): ?>
                    <div class="alert error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <form method="post" class="stack-md">
                    <label class="field">
                        <span>Usuario</span>
                        <input type="text" name="username" value="<?= esc(old('username')) ?>" autocomplete="username" required>
                        <?php if (isset($errors['username'])): ?>
                            <small><?= esc($errors['username']) ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field">
                        <span>Email</span>
                        <input type="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="email" required>
                        <?php if (isset($errors['email'])): ?>
                            <small><?= esc($errors['email']) ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field">
                        <span>Contrasena</span>
                        <input type="password" name="password" autocomplete="new-password" required>
                        <?php if (isset($errors['password'])): ?>
                            <small><?= esc($errors['password']) ?></small>
                        <?php endif; ?>
                    </label>

                    <label class="field">
                        <span>Repite la contrasena</span>
                        <input type="password" name="password_confirm" autocomplete="new-password" required>
                        <?php if (isset($errors['password_confirm'])): ?>
                            <small><?= esc($errors['password_confirm']) ?></small>
                        <?php endif; ?>
                    </label>

                    <button type="submit" class="button-primary">Entrar al proyecto</button>
                </form>

                <p class="switch-link">Ya tienes cuenta? <a href="<?= site_url('/login') ?>">Inicia sesion</a></p>
            </div>
        </section>
    </main>
</body>
</html>
