<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
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
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique' => true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique' => true
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false
            ],
            'role' => [
                'type'       => 'ENUM("admin", "mahasiswa", "dosen")',
                'default'    => 'mahasiswa',
                'null'       => false
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
