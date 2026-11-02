<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ResponseModel extends Model
{
    protected $table            = 'responses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'complaint_id',
        'user_id',
        'response_text',
        'attachment',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'complaint_id'  => 'required|is_natural_no_zero',
        'user_id'       => 'required|is_natural_no_zero',
        'response_text' => 'required|min_length[3]',
    ];

    protected $validationMessages = [
        'response_text' => [
            'required'   => 'Teks tanggapan wajib diisi.',
            'min_length' => 'Tanggapan terlalu pendek.',
        ],
    ];

    /**
     * Ambil seluruh tanggapan untuk satu laporan beserta nama petugas.
     *
     * @return list<array<string, mixed>>
     */
    public function forComplaint(int $complaintId): array
    {
        return $this->select('responses.*, users.nama AS responder_name, users.role AS responder_role')
            ->join('users', 'users.id = responses.user_id', 'left')
            ->where('complaint_id', $complaintId)
            ->orderBy('responses.created_at', 'ASC')
            ->findAll();
    }
}
