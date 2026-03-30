<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="chat-page">
    <main
        class="chat-shell"
        data-current-user-id="<?= esc((string) $currentUser['id']) ?>"
        data-poll-url="<?= site_url('/chat/poll') ?>"
        data-send-url="<?= site_url('/chat/messages') ?>"
    >
        <aside class="chat-sidebar">
            <div>
                <p class="eyebrow">Sala global</p>
                <h1>Pulse Chat</h1>
                <p class="lede">Un MVP de chat pensado para seguir creciendo desde CI4.</p>
            </div>

            <section class="profile-card">
                <span class="avatar-chip"><?= esc(strtoupper(substr($currentUser['username'], 0, 1))) ?></span>
                <div>
                    <strong><?= esc($currentUser['username']) ?></strong>
                    <p>Conectado ahora</p>
                </div>
            </section>

            <section class="online-card">
                <div class="section-heading">
                    <h2>Activos</h2>
                    <span id="online-count"><?= count($onlineUsers) ?></span>
                </div>
                <ul id="online-users" class="online-list"></ul>
            </section>

            <form action="<?= site_url('/logout') ?>" method="post">
                <button type="submit" class="button-secondary">Cerrar sesion</button>
            </form>
        </aside>

        <section class="chat-main">
            <header class="chat-header">
                <div>
                    <p class="eyebrow">Tiempo real ligero</p>
                    <h2>Conversacion compartida</h2>
                </div>
                <p class="status-pill">Actualizacion automatica cada 2 segundos</p>
            </header>

            <div id="chat-messages" class="messages"></div>

            <form id="message-form" class="composer">
                <label class="composer-field">
                    <span class="sr-only">Mensaje</span>
                    <textarea id="message-input" name="message" rows="1" maxlength="1000" placeholder="Escribe algo util, una idea o una prueba..." required></textarea>
                </label>
                <button type="submit" class="button-primary">Enviar</button>
            </form>

            <p id="form-error" class="inline-error" hidden></p>
        </section>
    </main>

    <script>
        window.chatBootstrap = {
            currentUserId: <?= json_encode($currentUser['id']) ?>,
            messages: <?= json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
            onlineUsers: <?= json_encode($onlineUsers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
        };
    </script>
    <script src="<?= base_url('js/chat.js') ?>" defer></script>
</body>
</html>
