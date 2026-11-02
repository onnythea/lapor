<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table            = 'ratings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'complaint_id',
        'score',
        'feedback',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = false; // tabel ratings hanya punya created_at

    protected $validationRules = [
        'complaint_id' => 'required|is_natural_no_zero|is_unique[ratings.complaint_id,id,{id}]',
        'score'        => 'required|in_list[1,2,3,4,5]',
        'feedback'     => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'complaint_id' => [
            'is_unique' => 'Laporan ini sudah pernah diberi penilaian.',
        ],
        'score' => [
            'required' => 'Skor rating wajib dipilih.',
            'in_list'  => 'Skor harus antara 1 sampai 5.',
        ],
    ];
}
