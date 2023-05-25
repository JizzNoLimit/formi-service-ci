<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Komentar extends Migration
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
            'konten' => [
                'type'    => 'TEXT',
                'null'    => false  
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true
            ],
            'diskusi_id' => [
                'type'       => 'BIGINT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'NO ACTION', 'SET NULL', 'fk_users_id_koment');
        $this->forge->addForeignKey('diskusi_id', 'diskusi', 'id', 'NO ACTION', 'SET NULL', 'fk_diskusi_id_koment');
        $this->forge->createTable('koment');
    }

    public function down()
    {
        $this->forge->dropForeignKey('koment', 'fk_users_id_koment');
        $this->forge->dropForeignKey('koment', 'fk_diskusi_id_koment');
        $this->forge->dropTable('koment');
    }
}
