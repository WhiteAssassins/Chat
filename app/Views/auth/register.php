<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | Pulse Chat</title>
    <meta name="description" content="Pulse Chat: a small private chat foundation with rooms, auth and presence.">
    <meta property="og:title" content="Pulse Chat">
    <meta property="og:description" content="Simple rooms, presence and realtime chat on CodeIgniter 4.">
    <meta property="og:image" content="<?= base_url('brand/pulse-social-card.svg') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="auth-page">
    <?php $errors = session('errors') ?? []; ?>
    <main class="auth-shell auth-shell-compact">
        <section class="auth-card-simple">
            <div class="auth-top">
                <div>
                    <img src="<?= base_url('brand/pulse-lockup.svg') ?>" alt="Pulse Chat" class="brand-lockup brand-lockup-auth">
                    <h1>Crear cuenta</h1>
                    <p class="auth-subcopy">Lo justo para empezar a usar el chat sin rodeos.</p>
                </div>
                <a class="auth-link-inline" href="<?= site_url('/login') ?>">Iniciar sesion</a>
            </div>

            <?php if (session('error')): ?>
                <div class="alert error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <form method="post" class="stack-md">
                <?= csrf_field() ?>
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

                <button type="submit" class="button-primary">Crear cuenta</button>
            </form>
        </section>
    </main>
</body>
</html>
