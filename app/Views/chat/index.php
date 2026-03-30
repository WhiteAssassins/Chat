<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pulse Chat</title>
    <meta name="description" content="Pulse Chat: simple rooms, presence and realtime basics built on CodeIgniter 4.">
    <meta property="og:title" content="Pulse Chat">
    <meta property="og:description" content="Simple rooms, presence and realtime chat on CodeIgniter 4.">
    <meta property="og:image" content="<?= base_url('brand/pulse-social-card.svg') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="chat-page">
    <main
        class="chat-shell"
        data-current-user-id="<?= esc((string) $currentUser['id']) ?>"
        data-current-room-id="<?= esc((string) $currentRoom['id']) ?>"
        data-poll-url="<?= site_url('/chat/poll') ?>"
        data-send-url="<?= site_url('/chat/messages') ?>"
        data-typing-url="<?= site_url('/chat/typing') ?>"
        data-csrf-header="<?= esc(config('Security')->headerName) ?>"
        data-csrf-cookie="<?= esc(config('Security')->cookieName) ?>"
    >
        <aside class="chat-sidebar">
            <div class="sidebar-top">
                <div>
                    <img src="<?= base_url('brand/pulse-lockup.svg') ?>" alt="Pulse Chat" class="brand-lockup brand-lockup-sidebar">
                    <p class="sidebar-tagline">Simple y directo</p>
                </div>
                <form action="<?= site_url('/logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="button-secondary">Salir</button>
                </form>
            </div>

            <section class="profile-card">
                <span class="avatar-chip"><?= esc(strtoupper(substr($currentUser['username'], 0, 1))) ?></span>
                <div>
                    <strong><?= esc($currentUser['username']) ?></strong>
                    <p>En <?= esc($currentRoom['name']) ?></p>
                </div>
            </section>

            <section class="room-card">
                <div class="section-heading">
                    <h2>Salas</h2>
                    <span><?= count($rooms) ?></span>
                </div>
                <nav class="room-list" aria-label="Salas disponibles">
                    <?php foreach ($rooms as $room): ?>
                        <a
                            href="<?= site_url('/chat') . '?room=' . urlencode($room['slug']) ?>"
                            class="room-link <?= (int) $room['id'] === (int) $currentRoom['id'] ? 'is-active' : '' ?>"
                        >
                            <strong><?= esc($room['name']) ?></strong>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </section>

            <section class="online-card">
                <div class="section-heading">
                    <h2>Activos aqui</h2>
                    <span id="online-count"><?= count($onlineUsers) ?></span>
                </div>
                <ul id="online-users" class="online-list"></ul>
            </section>
        </aside>

        <section class="chat-main">
            <header class="chat-header">
                <div>
                    <p class="eyebrow">Sala actual</p>
                    <h2><?= esc($currentRoom['name']) ?></h2>
                    <p class="room-summary"><?= esc($currentRoom['description']) ?></p>
                </div>
                <p class="status-pill"><?= count($onlineUsers) ?> activos</p>
            </header>

            <div id="chat-messages" class="messages"></div>

            <form id="message-form" class="composer">
                <label class="composer-field">
                    <span class="sr-only">Mensaje</span>
                    <textarea id="message-input" name="message" rows="1" maxlength="1000" placeholder="Escribe algo util, una idea o una prueba..." required></textarea>
                </label>
                <button type="submit" class="button-primary">Enviar</button>
            </form>

            <div class="composer-meta">
                <p id="typing-indicator" class="typing-indicator" hidden></p>
                <p id="char-counter" class="char-counter">0/1000</p>
            </div>

            <p id="form-error" class="inline-error" hidden></p>
        </section>
    </main>

    <script>
        window.chatBootstrap = {
            currentUserId: <?= json_encode($currentUser['id']) ?>,
            currentRoomId: <?= json_encode($currentRoom['id']) ?>,
            messages: <?= json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
            onlineUsers: <?= json_encode($onlineUsers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
            typingUsers: <?= json_encode($typingUsers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
            csrfHash: <?= json_encode(csrf_hash()) ?>
        };
    </script>
    <script src="<?= base_url('js/chat.js') ?>" defer></script>
</body>
</html>
