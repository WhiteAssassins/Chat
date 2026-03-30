<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password_hash',
        'active_room_id',
        'last_seen_at',
        'last_typing_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function findByLogin(string $login): ?array
    {
        $normalizedLogin = trim($login);

        return $this->builder()
            ->groupStart()
            ->where('username', $normalizedLogin)
            ->orWhere('LOWER(email)', strtolower($normalizedLogin))
            ->groupEnd()
            ->get()
            ->getRowArray();
    }

    public function markSeen(int $userId, ?int $roomId = null): void
    {
        $data = ['last_seen_at' => date('Y-m-d H:i:s')];

        if ($roomId !== null) {
            $data['active_room_id'] = $roomId;
        }

        $this->update($userId, $data);
    }

    public function markTyping(int $userId, int $roomId): void
    {
        $timestamp = date('Y-m-d H:i:s');

        $this->update($userId, [
            'active_room_id' => $roomId,
            'last_seen_at'   => $timestamp,
            'last_typing_at' => $timestamp,
        ]);
    }

    public function onlineUsers(int $roomId, int $windowSeconds = 120): array
    {
        return $this->builder()
            ->select('id, username, last_seen_at')
            ->where('active_room_id', $roomId)
            ->where('last_seen_at >=', date('Y-m-d H:i:s', time() - $windowSeconds))
            ->orderBy('username', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function typingUsers(int $roomId, int $excludeUserId = 0, int $windowSeconds = 6): array
    {
        $builder = $this->builder()
            ->select('id, username')
            ->where('active_room_id', $roomId)
            ->where('last_typing_at >=', date('Y-m-d H:i:s', time() - $windowSeconds));

        if ($excludeUserId > 0) {
            $builder->where('id !=', $excludeUserId);
        }

        return $builder
            ->orderBy('username', 'ASC')
            ->get()
            ->getResultArray();
    }
}
