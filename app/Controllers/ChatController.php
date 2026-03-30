<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\RoomModel;
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
        $roomModel    = new RoomModel();
        $userModel    = new UserModel();
        $messageModel = new MessageModel();
        $userId       = (int) session('user_id');
        $room         = $this->resolveRoom($roomModel, (string) $this->request->getGet('room'));

        $userModel->markSeen($userId, (int) $room['id']);

        return view('chat/index', [
            'currentUser' => [
                'id'       => $userId,
                'username' => (string) session('username'),
            ],
            'rooms'       => $roomModel->ordered(),
            'currentRoom' => $room,
            'messages'    => $messageModel->latestWithUsers((int) $room['id']),
            'onlineUsers' => $userModel->onlineUsers((int) $room['id']),
            'typingUsers' => $userModel->typingUsers((int) $room['id'], $userId),
        ]);
    }

    public function poll(): ResponseInterface
    {
        $roomModel    = new RoomModel();
        $userModel    = new UserModel();
        $messageModel = new MessageModel();
        $userId       = (int) session('user_id');
        $after        = max(0, (int) $this->request->getGet('after'));
        $roomId       = max(0, (int) $this->request->getGet('room_id'));
        $room         = $this->resolveRoom($roomModel, null, $roomId);

        $userModel->markSeen($userId, (int) $room['id']);

        return $this->response->setJSON([
            'messages'    => $messageModel->afterIdWithUsers((int) $room['id'], $after),
            'onlineUsers' => $userModel->onlineUsers((int) $room['id']),
            'typingUsers' => $userModel->typingUsers((int) $room['id'], $userId),
            'csrfHash'    => csrf_hash(),
        ]);
    }

    public function store(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Solicitud no permitida.']);
        }

        $roomModel    = new RoomModel();
        $messageModel = new MessageModel();
        $userModel    = new UserModel();
        $userId       = (int) session('user_id');
        $body         = trim((string) $this->request->getPost('message'));
        $roomId       = max(0, (int) $this->request->getPost('room_id'));
        $room         = $this->resolveRoom($roomModel, null, $roomId);

        if (! service('throttler')->check($this->throttleKey('chat-message', (string) $userId), 20, 60)) {
            return $this->response
                ->setStatusCode(429)
                ->setJSON(['error' => 'Estas enviando mensajes demasiado rapido. Espera un poco.']);
        }

        if ($body === '' || mb_strlen($body) > 1000) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Escribe un mensaje entre 1 y 1000 caracteres.']);
        }

        $userModel->markSeen($userId, (int) $room['id']);

        $messageId = $messageModel->insert([
            'user_id' => $userId,
            'room_id' => (int) $room['id'],
            'body'    => $body,
        ], true);

        return $this->response->setJSON([
            'message'     => $messageModel->findWithUser((int) $messageId),
            'onlineUsers' => $userModel->onlineUsers((int) $room['id']),
            'typingUsers' => $userModel->typingUsers((int) $room['id'], $userId),
            'csrfHash'    => csrf_hash(),
        ]);
    }

    public function typing(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Solicitud no permitida.']);
        }

        $roomModel = new RoomModel();
        $userModel = new UserModel();
        $userId    = (int) session('user_id');
        $roomId    = max(0, (int) $this->request->getPost('room_id'));
        $room      = $this->resolveRoom($roomModel, null, $roomId);

        if (! service('throttler')->check($this->throttleKey('chat-typing', (string) $userId), 120, 60)) {
            return $this->response->setStatusCode(429)->setJSON([
                'typingUsers' => $userModel->typingUsers((int) $room['id'], $userId),
                'csrfHash'    => csrf_hash(),
            ]);
        }

        $userModel->markTyping($userId, (int) $room['id']);

        return $this->response->setJSON([
            'typingUsers' => $userModel->typingUsers((int) $room['id'], $userId),
            'csrfHash'    => csrf_hash(),
        ]);
    }

    private function resolveRoom(RoomModel $roomModel, ?string $slug = null, ?int $roomId = null): array
    {
        $room = null;

        if ($roomId) {
            $room = $roomModel->find($roomId);
        } elseif ($slug) {
            $room = $roomModel->findBySlug($slug);
        }

        return $room ?? $roomModel->defaultRoom();
    }
}
