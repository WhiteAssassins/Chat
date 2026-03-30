<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/chat');
        }

        if ($this->request->is('post')) {
            $rules = [
                'login'    => 'required|max_length[190]',
                'password' => 'required|min_length[6]|max_length[255]',
            ];

            if (! $this->validateData($this->request->getPost(), $rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $login     = trim((string) $this->request->getPost('login'));
            $password  = (string) $this->request->getPost('password');
            $userModel = new UserModel();
            $user      = $userModel->findByLogin($login);

            if (! $user || ! password_verify($password, $user['password_hash'])) {
                return redirect()->back()->withInput()->with('error', 'Credenciales invalidas.');
            }

            $userModel->markSeen((int) $user['id']);

            session()->regenerate();
            session()->set([
                'user_id'  => (int) $user['id'],
                'username' => $user['username'],
            ]);

            return redirect()->to('/chat');
        }

        return view('auth/login');
    }

    public function register()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/chat');
        }

        if ($this->request->is('post')) {
            $rules = [
                'username'         => 'required|min_length[3]|max_length[40]|alpha_numeric_space|is_unique[users.username]',
                'email'            => 'required|valid_email|max_length[190]|is_unique[users.email]',
                'password'         => 'required|min_length[6]|max_length[255]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (! $this->validateData($this->request->getPost(), $rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $username  = trim((string) $this->request->getPost('username'));
            $userModel = new UserModel();
            $userId    = $userModel->insert([
                'username'      => $username,
                'email'         => strtolower(trim((string) $this->request->getPost('email'))),
                'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'last_seen_at'  => date('Y-m-d H:i:s'),
            ], true);

            session()->regenerate();
            session()->set([
                'user_id'  => (int) $userId,
                'username' => $username,
            ]);

            return redirect()->to('/chat');
        }

        return view('auth/register');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Sesion cerrada.');
    }
}
