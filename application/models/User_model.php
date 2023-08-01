public function get_users() {
    // ...

    foreach ($users as &$user) {
        $user_id = $user['id'];

        // Obtener el nombre de usuario del remitente en mensajes privados
        $private_messages = $this->Chat_model->get_private_messages($user_id);
        if (!empty($private_messages)) {
            $user['from_username'] = $private_messages[0]['from_username'];
        } else {
            $user['from_username'] = '';
        }
    }

    return $users;
}
