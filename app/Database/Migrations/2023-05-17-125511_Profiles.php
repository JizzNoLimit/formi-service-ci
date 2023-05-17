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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'tgl_lahir' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true
            ],
            'lokasi' => [
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
            'user_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 10,
                'unsigned'       => true,
            ],
            'create_at' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true
            ],
            'update_at' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('profile');
    }

    public function down()
    {
        $this->forge->dropForeignKey('profile', 'profile_user_id_foreign');
        $this->forge->dropTable('profile');
    }
}
