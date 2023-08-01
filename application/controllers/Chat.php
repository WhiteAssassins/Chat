<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('input_helper');
    }

    public function index() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    
        // Obtén el ID del usuario actual desde la variable de sesión
        $user_id = $this->session->userdata('user_id');
    
        // Agregar al usuario a la tabla de online_users o actualizar su última actividad
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('online_users');
        if ($query->num_rows() === 0) {
            $this->db->insert('online_users', array('user_id' => $user_id));
        } else {
            $this->db->where('user_id', $user_id);
            $this->db->update('online_users', array('last_activity' => date('Y-m-d H:i:s')));
        }
    
        // Obtén la lista de usuarios online desde la tabla online_users y el número de mensajes enviados por cada usuario
        $this->db->select('online_users.user_id, users.username, COUNT(chat_messages.id) as message_count');
        $this->db->from('online_users');
        $this->db->join('users', 'online_users.user_id = users.id', 'left');
        $this->db->join('chat_messages', 'online_users.user_id = chat_messages.user_id', 'left');
        $this->db->group_by('online_users.user_id, users.username');
        $query = $this->db->get();
        $online_users = $query->result_array();
    
        // Obtén los mensajes del chat desde la base de datos
        $this->db->select('chat_messages.message, users.username');
        $this->db->from('chat_messages');
        $this->db->join('users', 'chat_messages.user_id = users.id');
        $query = $this->db->get();
        $chat_messages = $query->result_array();
    
        // Carga la vista del chat y pasa la lista de usuarios online y mensajes como datos a la vista
        $data['online_users'] = $online_users;
        $data['chat_messages'] = $chat_messages;
        $this->load->view('chat_view', $data);




        
    }
    
    
    

    public function send_message() {
        $user_id = $this->session->userdata('user_id');
        $message = $this->input->post('message');
    
        // Verificar que el mensaje no esté vacío
        if (!empty($message)) {
            // Guardar el mensaje en la base de datos con el ID del usuario
            $this->db->insert('chat_messages', array('user_id' => $user_id, 'message' => $message));
    
            // Devolver el mensaje como respuesta para mostrarlo en el chat en tiempo real
            echo json_encode(array('username' => $this->session->userdata('username'), 'message' => $message));
        }
    }
    

    public function send_message_ajax() {
        $sender = $this->input->post('sender');
        $message = $this->input->post('message');
    
        if (!empty($sender) && !empty($message)) {
            $data = array(
                'sender' => $sender,
                'message' => $message
            );
    
            $this->db->insert('chat_messages', $data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sender and message cannot be empty.']);
        }
    }

    public function get_chat_messages() {
        // Obtener los mensajes del chat desde la base de datos y los nombres de usuario asociados
        $this->db->select('chat_messages.message, users.username');
        $this->db->from('chat_messages');
        $this->db->join('users', 'chat_messages.user_id = users.id');
        $query = $this->db->get();
        $chat_messages = $query->result_array();
    
        // Devolver los mensajes como respuesta en formato JSON para actualizar el chat en tiempo real
        echo json_encode(array('chat_messages' => $chat_messages));
    }
    
public function logout() {
        $this->session->unset_userdata('user_id');
        redirect('auth/login');
    }

    public function get_online_users() {
        $this->db->select('online_users.user_id, users.username');
        $this->db->from('online_users');
        $this->db->join('users', 'online_users.user_id = users.id', 'left');
        $query = $this->db->get();
        $online_users = $query->result_array();
    
        $data['online_users'] = $online_users;
        echo json_encode($data);
    }
    // Envío de mensajes privados
public function send_private_message() {
    if (!$this->session->userdata('user_id')) {
        redirect('auth');
    }

    $from_user_id = $this->session->userdata('user_id');
    $to_user_id = $this->input->post('to_user_id');
    $message = $this->input->post('message');

    $data = array(
        'from_user_id' => $from_user_id,
        'to_user_id' => $to_user_id,
        'message' => $message,
        'created_at' => date('Y-m-d H:i:s')
    );

    $this->db->insert('private_messages', $data);

    // Redireccionar al chat después de enviar el mensaje privado
    redirect('chat');
}

// Recepción de mensajes privados
public function get_private_messages($user_id) {
    if (!$this->session->userdata('user_id')) {
        redirect('auth');
    }

    $this->db->select('private_messages.*, users.username AS from_username');
    $this->db->from('private_messages');
    $this->db->join('users', 'private_messages.from_user_id = users.id');
    $this->db->where('private_messages.to_user_id', $user_id);
    $this->db->order_by('private_messages.created_at', 'desc');
    $query = $this->db->get();
    return $query->result_array();
}


}
