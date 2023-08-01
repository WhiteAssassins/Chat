<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('public/css/main.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>













<div class="sidebar">
    <h2>Perfil del Usuario</h2>
    <!-- Mostrar el perfil del usuario aquí (puedes obtener los datos desde la base de datos) -->
    <div class="online-users">
        <h2>Usuarios en línea</h2>
        <ul id="user-list">
            <?php foreach ($online_users as $user): ?>
                <li>
                    <span class="username"><?php echo $user['username']; ?></span>
                    <span class="message-count-pill"><?php echo $user['message_count']; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a href="<?php echo base_url('auth/logout'); ?>">Cerrar sesión</a>
</div>



<div id="chat-messages">
<div class="message-sender">

    <?php foreach ($chat_messages as $message): ?>
        <p><strong><?php echo $message['username']; ?>:</strong> <?php echo $message['message']; ?></p>
    <?php endforeach; ?>
</div>

<div id="chat-form">
    <input type="text" id="message" placeholder="Escribe tu mensaje aquí" required>
    <button id="send-button">Enviar</button>
</div>












<script>
    // Enviar mensaje utilizando AJAX al hacer clic en el botón
    $('#send-button').on('click', function() {
        var message = $('#message').val();
        $.ajax({
            url: '<?php echo base_url("chat/send_message"); ?>',
            type: 'POST',
            data: {message: message},
            success: function(data) {
                // Actualizar el chat con el nuevo mensaje sin recargar la página
                $('#chat-messages').append('<p><strong><?php echo $this->session->userdata('username'); ?>:</strong> ' + message + '</p>');
                $('#message').val(''); // Limpiar el campo de texto después de enviar el mensaje
            }
        });
    });

    // Actualizar automáticamente el chat cada 5 segundos utilizando AJAX
    function updateChat() {
        $.ajax({
            url: '<?php echo base_url("chat/get_chat_messages"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar el chat actual
                $('#chat-messages').empty();

                // Mostrar los mensajes recibidos desde el servidor
                $.each(data.chat_messages, function(index, message) {
                    $('#chat-messages').append('<p><strong>' + message.username + ':</strong> ' + message.message + '</p>');
                });
            },
            complete: function() {
                // Programar la siguiente actualización en 5 segundos
                setTimeout(updateChat, 200); // 5000 milisegundos = 5 segundos
            }
        });
    }

    // Llamar a la función para la primera actualización del chat
    updateChat();
</script>


   
    </body>
</html>


