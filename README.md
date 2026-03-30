# Pulse Chat

Chat sencillo hecho desde cero sobre CodeIgniter 4.

## Incluye

- Registro e inicio de sesion
- Sala global compartida
- Actualizacion automatica del chat cada 2 segundos
- Lista de usuarios activos segun actividad reciente
- SQLite local para arrancar sin montar MySQL

## Stack

- PHP 8.2+
- CodeIgniter 4.7.2
- SQLite3
- JavaScript vanilla

## Arranque rapido

1. Instala dependencias:

```bash
composer install
```

2. Crea la base SQLite si no existe:

```powershell
New-Item -Path writable\chat.db -ItemType File -Force
```

3. Corre migraciones:

```bash
php spark migrate
```

4. Levanta el servidor:

```bash
php spark serve
```

5. Abre:

```text
http://127.0.0.1:8080
```

## Estructura principal

- `app/Controllers/AuthController.php`: registro, login y logout
- `app/Controllers/ChatController.php`: sala principal, polling y envio de mensajes
- `app/Models/UserModel.php`: usuarios y presencia
- `app/Models/MessageModel.php`: mensajes del chat
- `app/Database/Migrations/`: esquema de usuarios y mensajes
- `app/Views/`: pantallas de auth y chat
- `public/css/app.css`: estilos
- `public/js/chat.js`: cliente del chat

## Ideas para la siguiente iteracion

- Salas multiples
- Mensajes privados
- Avatares reales
- WebSockets o SSE
- Notificaciones de escritura
