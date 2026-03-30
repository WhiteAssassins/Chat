<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'accent',
        'sort_order',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;

    public function ordered(): array
    {
        return $this->builder()
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->builder()
            ->where('slug', $slug)
            ->get()
            ->getRowArray();
    }

    public function defaultRoom(): array
    {
        return $this->builder()
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getFirstRow('array');
    }
}
