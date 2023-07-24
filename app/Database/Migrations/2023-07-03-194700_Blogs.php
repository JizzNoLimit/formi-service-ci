<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Blogs extends Migration
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
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false
            ],
            'konten' => [
                'type'       => 'TEXT',
                'null'       => false
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
                'unique'     => true
            ],
            'img' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 10,
                'unsigned'   => true,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'NO ACTION', 'SET NULL', 'fk_users_id_blogs');
        $this->forge->createTable('blogs');
    }

    public function down()
    {
        $this->forge->dropForeignKey('blogs', 'fk_users_id_blogs');
        $this->forge->dropTable('blogs');
    }
}
