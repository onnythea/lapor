<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = false;

    /**
     * Catat aktivitas (audit trail). Aman dipanggil dari mana saja.
     */
    public function record(?int $userId, string $action, ?string $description = null): void
    {
        $request = service('request');

        /*
        $this->insert([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => (string) $request->getUserAgent(),
        ]);
        */

        $data = [
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $request->getIPAddress(),
            'user_agent'  => (string) $request->getUserAgent(),
        ];

        log_message('debug', 'ACTIVITY LOG DATA: ' . print_r($data, true));

        $builder = $this->db->table($this->table);

        $builder->insert($data);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function withUser(): array
    {
        return $this->select('activity_logs.*, users.nama AS user_name, users.role AS user_role')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->findAll(300);
    }
}
