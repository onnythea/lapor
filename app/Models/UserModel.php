<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'nama',
        'email',
        'password_hash',
        'role',
        'nim_nik',
        'fakultas_prodi',
        'no_hp',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'nama'  => 'required|min_length[3]|max_length[150]',
        'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
        'role'  => 'required|in_list[admin,petugas,mahasiswa,umum]',
        'nim_nik' => 'permit_empty|alpha_numeric|max_length[30]',
        'no_hp'   => 'permit_empty|numeric|max_length[20]',
    ];

    protected $validationMessages = [
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email sudah terdaftar, gunakan email lain.',
        ],
        'nama' => [
            'required'   => 'Nama wajib diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
        ],
        'role' => [
            'in_list' => 'Role tidak dikenali.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Ambil user aktif berdasarkan email (untuk proses login).
     */
    public function findActiveByEmail(string $email): ?array
    {
        return $this->where('email', $email)
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Set / perbarui password dengan hashing aman.
     */
    public function setPassword(int $userId, string $plainPassword): bool
    {
        return $this->update($userId, [
            'password_hash' => password_hash($plainPassword, PASSWORD_DEFAULT),
        ]);
    }
}
