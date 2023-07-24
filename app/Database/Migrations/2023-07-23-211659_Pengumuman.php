<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pengumuman extends Migration
{
    public function up() {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'TEXT',
                'constraint' => '300',
                'null'       => false
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
                'unique'     => true
            ],
            'konten' => [
                'type'       => 'TEXT',
                'null'       => false
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'file' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'created_at' => [
                'type'      => 'BIGINT',
                'unsigned'  => true,
                'null'      => true
            ],
            'updated_at' => [
                'type'      => 'BIGINT',
                'unsigned'  => true,
                'null'      => true
            ]
            
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengumuman');
    }

    public function down() {
        $this->forge->dropTable('pengumuman');
    }
}
