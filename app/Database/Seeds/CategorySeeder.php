<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            ['Akademik', 'Perkuliahan, nilai, KRS, dosen, dan layanan akademik lainnya.'],
            ['Fasilitas', 'Sarana prasarana kampus: ruang kelas, toilet, wifi, parkir.'],
            ['Kekerasan Seksual / PPKS', 'Pelaporan kekerasan seksual dan penanganan oleh Satgas PPKS.'],
            ['Administrasi', 'Layanan administrasi, pembayaran, surat menyurat, dan legalisir.'],
            ['Layanan Publik', 'Pengaduan layanan publik dan aspirasi masyarakat umum.'],
        ];

        foreach ($categories as [$name, $description]) {
            $slug   = url_title($name, '-', true);
            $exists = $this->db->table('categories')->where('slug', $slug)->countAllResults() > 0;

            if (! $exists) {
                $this->db->table('categories')->insert([
                    'name'        => $name,
                    'slug'        => $slug,
                    'description' => $description,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }
    }
}
