<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserVerifications extends Migration
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
                'null'       => true
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'tgl_lahir' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'status' => [
                'type'       => 'BOOLEAN',
                'default'    => false
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
        $this->forge->createTable('users_verifications');
    }

    public function down()
    {
        $this->forge->dropTable('users_verifications');
    }
}
