<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name',
        'slug',
        'description',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[120]',
        'slug' => 'required|alpha_dash|max_length[140]|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama kategori wajib diisi.',
        ],
        'slug' => [
            'is_unique' => 'Slug kategori sudah digunakan.',
        ],
    ];

    /**
     * Callback untuk membuat slug otomatis dari name bila kosong.
     */
    protected $beforeInsert = ['generateSlug'];
    protected $beforeUpdate = ['generateSlug'];

    protected function generateSlug(array $data): array
    {
        if (! isset($data['data']['slug']) || $data['data']['slug'] === '') {
            $name = $data['data']['name'] ?? '';
            if ($name !== '') {
                $data['data']['slug'] = url_title($name, '-', true);
            }
        }

        return $data;
    }
}
