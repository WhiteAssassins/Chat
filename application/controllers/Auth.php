<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function register()
    {
        $data['error'] = '';
    
        if ($_POST) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[100]|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('profile_image', 'Profile Image', 'callback_file_required');
    
            if ($this->form_validation->run() == FALSE) {
                $data['error'] = validation_errors();
            } else {
                // Procesar la imagen de perfil si se ha enviado
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'jpg|png|jpeg|gif';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;
    
                $this->load->library('upload', $config);
    
                if ($this->upload->do_upload('profile_image')) {
                    $data = $this->upload->data();
                    $profile_image = $data['file_name'];
                } else {
                    $profile_image = 'default_profile.png'; // Si no se carga una imagen, usar una por defecto
                }
    
                $username = $this->input->post('username');
                $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                $email = $this->input->post('email');
    
                $data = array(
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'profile_image' => $profile_image // Agregar el campo de imagen de perfil a los datos
                );
    
                $this->db->insert('users', $data);
                redirect('auth/login');
            }
        }
    
        $this->load->view('register_view', $data);
    }
    
    // Función de callback para verificar si el archivo se ha cargado
    public function file_required($str)
    {
        if (empty($_FILES['profile_image']['name'])) {
            $this->form_validation->set_message('file_required', 'El campo de imagen de perfil es obligatorio.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    

    public function login() {
        $data['error'] = '';
        if ($_POST) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->db->where('username', $username)->get('users')->row();

            if ($user && password_verify($password, $user->password)) {
                $this->session->set_userdata('user_id', $user->id);
                $this->db->insert('online_users', array('user_id' => $user->id));
                redirect('chat');
            } else {
                $data['error'] = 'Invalid username or password.';
            }
        }

        $this->load->view('login_view', $data);
    }

    public function logout() {
        $user_id = $this->session->userdata('user_id');
        $this->session->unset_userdata('user_id');
    
        // Eliminar al usuario de la tabla de online_users
        $this->db->where('user_id', $user_id);
        $this->db->delete('online_users');
        redirect('auth/login');
    }
    public function index() {
        if ($this->session->userdata('user_id') == '') {
            $this->load->view('login_view');
        } else {
            redirect('chat');
            
        }
    }
    
}
