<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Diskusi extends Migration
{
    public function up()
    {
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
            'desk' => [
                'type'       => 'TEXT',
                'null'       => false
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'NO ACTION', 'SET NULL', 'fk_users_id_diskusi');
        $this->forge->createTable('diskusi');
    }

    public function down()
    {
        $this->forge->dropForeignKey('diskusi', 'fk_users_id_diskusi');
        $this->forge->dropTable('diskusi');
    }
}
