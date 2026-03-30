<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Pulse Chat</title>
    <meta name="description" content="Pulse Chat: rooms, presence and realtime basics on a simple CI4 foundation.">
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
                    <h1>Entrar</h1>
                    <p class="auth-subcopy">Salas, presencia y cuentas en una interfaz mas simple.</p>
                </div>
                <a class="auth-link-inline" href="<?= site_url('/register') ?>">Crear cuenta</a>
            </div>

            <?php if (session('message')): ?>
                <div class="alert success"><?= esc(session('message')) ?></div>
            <?php endif; ?>

            <?php if (session('error')): ?>
                <div class="alert error"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <form method="post" class="stack-md">
                <?= csrf_field() ?>
                <label class="field">
                    <span>Usuario o email</span>
                    <input type="text" name="login" value="<?= esc(old('login')) ?>" autocomplete="username" required>
                    <?php if (isset($errors['login'])): ?>
                        <small><?= esc($errors['login']) ?></small>
                    <?php endif; ?>
                </label>

                <label class="field">
                    <span>Contrasena</span>
                    <input type="password" name="password" autocomplete="current-password" required>
                    <?php if (isset($errors['password'])): ?>
                        <small><?= esc($errors['password']) ?></small>
                    <?php endif; ?>
                </label>

                <button type="submit" class="button-primary">Entrar</button>
            </form>

            <p class="auth-footnote">Demo rapido: <strong>demo</strong> / <strong>demo1234</strong></p>
        </section>
    </main>
</body>
</html>
