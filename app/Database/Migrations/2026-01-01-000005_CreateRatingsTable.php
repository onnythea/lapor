<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRatingsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'complaint_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'score' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('complaint_id'); // 1 laporan = 1 rating
        $this->forge->addForeignKey('complaint_id', 'complaints', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('ratings', true, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        $this->forge->dropTable('ratings', true);
    }
}
