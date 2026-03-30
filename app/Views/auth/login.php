<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Pulse Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="auth-page">
    <?php $errors = session('errors') ?? []; ?>
    <main class="auth-shell">
        <section class="auth-panel auth-panel-copy">
            <p class="eyebrow">CodeIgniter 4</p>
            <h1>Un chat sencillo, limpio y listo para crecer.</h1>
            <p class="lede">Cuentas, presencia en linea y mensajes compartidos en una sola sala para arrancar rapido desde una base moderna.</p>
            <ul class="feature-list">
                <li>Registro e inicio de sesion con hash seguro.</li>
                <li>Mensajes compartidos con actualizacion automatica.</li>
                <li>Lista de usuarios activos basada en actividad reciente.</li>
            </ul>
        </section>

        <section class="auth-panel auth-panel-form">
            <div class="auth-card">
                <p class="card-kicker">Bienvenido</p>
                <h2>Entrar al chat</h2>

                <?php if (session('message')): ?>
                    <div class="alert success"><?= esc(session('message')) ?></div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                    <div class="alert error"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <form method="post" class="stack-md">
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

                <p class="switch-link">No tienes cuenta todavia? <a href="<?= site_url('/register') ?>">Crear una</a></p>
            </div>
        </section>
    </main>
</body>
</html>
