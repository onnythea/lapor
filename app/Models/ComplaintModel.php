<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'ticket_number',
        'user_id',
        'category_id',
        'title',
        'content',
        'attachment',
        'status',
        'assigned_unit',
        'is_anonymous',
        'is_secret',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'     => 'required|is_natural_no_zero',
        'category_id' => 'required|is_natural_no_zero',
        'title'       => 'required|min_length[5]|max_length[200]',
        'content'     => 'required|min_length[15]',
        'status'      => 'permit_empty|in_list[draft,pending,processing,completed,rejected]',
        'is_anonymous' => 'permit_empty|in_list[0,1]',
        'is_secret'    => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'title' => [
            'required'   => 'Judul laporan wajib diisi.',
            'min_length' => 'Judul minimal 5 karakter.',
        ],
        'content' => [
            'required'   => 'Isi laporan wajib diisi.',
            'min_length' => 'Isi laporan minimal 15 karakter agar dapat ditindaklanjuti.',
        ],
        'category_id' => [
            'required' => 'Kategori wajib dipilih.',
        ],
    ];

    /**
     * Ambil laporan lengkap dengan relasi kategori & pelapor.
     */
    public function withRelations(): self
    {
        $this->select('complaints.*, categories.name AS category_name, users.nama AS reporter_name, users.email AS reporter_email')
            ->join('categories', 'categories.id = complaints.category_id', 'left')
            ->join('users', 'users.id = complaints.user_id', 'left');

        return $this;
    }

    public function findByTicket(string $ticket): ?array
    {
        return $this->withRelations()
            ->where('complaints.ticket_number', $ticket)
            ->first();
    }

    /**
     * Statistik jumlah laporan per status untuk dashboard.
     *
     * @return array<string, int>
     */
    public function statusCounts(): array
    {
        $rows = $this->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->findAll();

        $counts = ['pending' => 0, 'processing' => 0, 'completed' => 0, 'rejected' => 0, 'draft' => 0];
        foreach ($rows as $row) {
            $counts[$row['status']] = (int) $row['total'];
        }

        return $counts;
    }
}
