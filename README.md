# Pulse Chat

Chat sencillo hecho desde cero sobre CodeIgniter 4, pensado para servir como base limpia de un producto privado o un MVP interno.

## Incluye

- Registro e inicio de sesion
- Tres salas listas: General, Ideas y Soporte
- Actualizacion automatica del chat cada 2 segundos
- Lista de usuarios activos por sala
- Indicador de escritura en vivo
- Envio rapido con `Enter` y salto de linea con `Shift + Enter`
- CSRF activo en formularios y peticiones AJAX
- Rate limit basico para login, registro y envio de mensajes
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

2. Copia el archivo de entorno si quieres personalizar `baseURL` o pasar luego a produccion:

```powershell
Copy-Item env .env
```

3. Crea la base SQLite si no existe:

```powershell
New-Item -Path writable\chat.db -ItemType File -Force
```

4. Corre migraciones:

```bash
php spark migrate
```

5. Levanta el servidor:

```bash
php spark serve
```

6. Abre:

```text
http://127.0.0.1:8080
```

## Arranque rapido desde la carpeta GitHub

Si quieres subir o bajar los tres proyectos PHP del workspace de una vez, usa estos archivos:

- `C:\Users\chris\OneDrive\Documentos\GitHub\start-php-projects.cmd`
- `C:\Users\chris\OneDrive\Documentos\GitHub\stop-php-projects.cmd`

Tambien puedes ejecutarlos desde consola:

```cmd
cd C:\Users\chris\OneDrive\Documentos\GitHub
start-php-projects.cmd
```

Para detenerlos:

```cmd
cd C:\Users\chris\OneDrive\Documentos\GitHub
stop-php-projects.cmd
```

## Usuario demo

- Usuario: `demo`
- Email: `demo@pulse.local`
- Contrasena: `demo1234`

## Preparado para GitHub

Este repo ya esta orientado a subirse como privado sin arrastrar basura local:

- `.env` no se versiona
- `vendor/` no se versiona
- logs, cache y sesiones no se versionan
- la base SQLite local `writable/chat.db` no debe subirse

Antes de hacer el primer push, asegúrate de que `writable/chat.db` no quede trackeado por Git si viene de pruebas locales.

## Push privado rapido

Ejemplo minimo si ya creaste el repo privado en GitHub:

```bash
git add .
git commit -m "Initial private chat MVP"
git branch -M main
git remote add origin <URL_PRIVADA_DEL_REPO>
git push -u origin main
```

## Seguridad actual

- CSRF habilitado globalmente
- cabeceras seguras habilitadas
- validacion basica de entrada
- limitacion de intentos en login y registro
- limitacion de envio rapido de mensajes

## Branding incluido

Assets base listos para seguir iterando:

- `public/brand/pulse-mark.svg`: isotipo principal
- `public/brand/pulse-lockup.svg`: wordmark horizontal
- `public/brand/pulse-social-card.svg`: imagen social o portada base
- `public/favicon.svg`: favicon SVG

## Estructura principal

- `app/Controllers/AuthController.php`: registro, login y logout
- `app/Controllers/ChatController.php`: salas, polling, typing y envio de mensajes
- `app/Models/UserModel.php`: usuarios, presencia y actividad por sala
- `app/Models/MessageModel.php`: mensajes filtrados por sala
- `app/Models/RoomModel.php`: catalogo de salas disponibles
- `app/Database/Migrations/`: esquema de usuarios, mensajes y salas
- `app/Views/`: pantallas de auth y chat
- `public/css/app.css`: estilos
- `public/js/chat.js`: cliente del chat

## Ideas para la siguiente iteracion

- Mensajes privados
- Edicion o borrado de mensajes propios
- Avatares reales
- WebSockets o SSE
- Archivos adjuntos o previews
