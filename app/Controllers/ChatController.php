<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ChatController extends BaseController
{
    public function home(): ResponseInterface
    {
        return redirect()->to(session()->has('user_id') ? '/chat' : '/login');
    }

    public function index(): string
    {
        $userModel    = new UserModel();
        $messageModel = new MessageModel();
        $userId       = (int) session('user_id');

        $userModel->markSeen($userId);

        return view('chat/index', [
            'currentUser' => [
                'id'       => $userId,
                'username' => (string) session('username'),
            ],
            'messages'    => $messageModel->latestWithUsers(),
            'onlineUsers' => $userModel->onlineUsers(),
        ]);
    }

    public function poll(): ResponseInterface
    {
        $userModel    = new UserModel();
        $messageModel = new MessageModel();
        $userId       = (int) session('user_id');
        $after        = max(0, (int) $this->request->getGet('after'));

        $userModel->markSeen($userId);

        return $this->response->setJSON([
            'messages'    => $messageModel->afterIdWithUsers($after),
            'onlineUsers' => $userModel->onlineUsers(),
        ]);
    }

    public function store(): ResponseInterface
    {
        $messageModel = new MessageModel();
        $userModel    = new UserModel();
        $userId       = (int) session('user_id');
        $body         = trim((string) $this->request->getPost('message'));

        if ($body === '' || mb_strlen($body) > 1000) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Escribe un mensaje entre 1 y 1000 caracteres.']);
        }

        $userModel->markSeen($userId);

        $messageId = $messageModel->insert([
            'user_id' => $userId,
            'body'    => $body,
        ], true);

        return $this->response->setJSON([
            'message'     => $messageModel->findWithUser((int) $messageId),
            'onlineUsers' => $userModel->onlineUsers(),
        ]);
    }
}
