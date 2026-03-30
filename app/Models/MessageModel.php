<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'room_id',
        'body',
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

    public function latestWithUsers(int $roomId, int $limit = 50): array
    {
        $rows = $this->builder()
            ->select('messages.id, messages.room_id, messages.body, messages.created_at, users.id AS user_id, users.username')
            ->join('users', 'users.id = messages.user_id')
            ->where('messages.room_id', $roomId)
            ->orderBy('messages.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_reverse($rows);
    }

    public function afterIdWithUsers(int $roomId, int $afterId): array
    {
        return $this->builder()
            ->select('messages.id, messages.room_id, messages.body, messages.created_at, users.id AS user_id, users.username')
            ->join('users', 'users.id = messages.user_id')
            ->where('messages.room_id', $roomId)
            ->where('messages.id >', $afterId)
            ->orderBy('messages.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function findWithUser(int $messageId): ?array
    {
        return $this->builder()
            ->select('messages.id, messages.room_id, messages.body, messages.created_at, users.id AS user_id, users.username')
            ->join('users', 'users.id = messages.user_id')
            ->where('messages.id', $messageId)
            ->get()
            ->getRowArray();
    }
}
