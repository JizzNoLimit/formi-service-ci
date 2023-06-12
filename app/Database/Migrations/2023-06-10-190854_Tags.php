<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tags extends Migration
{
    public function up() {
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => false,
                'unique'     => true
            ],
            'desk' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'total_tags' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tags');
    }

    public function down() {
        $this->forge->dropTable('tags');
    }
}
