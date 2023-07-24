<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ViewsDiskusi extends Migration
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
            'divice_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
                'null'       => false,
            ],
            'diskusi_id' => [
                'type'          => 'BIGINT',
                'constraint'    => 10,
                'unsigned'      => true,
                'null'          => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('diskusi_id', 'diskusi', 'id', 'CASCADE', 'CASCADE', 'fk_diskusi_id_viewsDiskusi');
        $this->forge->createTable('views');
    }

    public function down()
    {
        $this->forge->dropForeignKey('views', 'fk_diskusi_id_viewsDiskusi');
        $this->forge->dropTable('views');
    }
}
