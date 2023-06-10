<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Profiles extends Migration
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
            'alamat' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'bio' => [
                'type'       => 'TEXT',
                'null'       => true
            ],
            'foto' => [
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
        $this->forge->createTable('profile');
    }

    public function down()
    {
        $this->forge->dropTable('profile');
    }
}
