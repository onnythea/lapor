<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $rows = [
            [
                'nama'          => 'Super Administrator',
                'email'         => 'admin@lapor.test',
                'password_hash' => password_hash('Admin#12345', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'nim_nik'       => null,
                'no_hp'         => '081200000000',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Petugas Unit Layanan',
                'email'         => 'petugas@lapor.test',
                'password_hash' => password_hash('Petugas#12345', PASSWORD_DEFAULT),
                'role'          => 'petugas',
                'nim_nik'       => null,
                'no_hp'         => '081211111111',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        // Idempotent: hindari duplikasi bila seeder dijalankan berulang.
        foreach ($rows as $row) {
            $exists = $this->db->table('users')->where('email', $row['email'])->countAllResults() > 0;
            if (! $exists) {
                $this->db->table('users')->insert($row);
            }
        }
    }
}
