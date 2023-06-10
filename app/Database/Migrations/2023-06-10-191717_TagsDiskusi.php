<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TagsDiskusi extends Migration
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
            'tags_id' => [
                'type'          => 'BIGINT',
                'constraint'    => 10,
                'unsigned'      => true,
                'null'          => true,
            ],
            'diskusi_id' => [
                'type'          => 'BIGINT',
                'constraint'    => 10,
                'unsigned'      => true,
                'null'          => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tags_id', 'tags', 'id', 'CASCADE', 'CASCADE', 'fk_tags_id_tagsdiskusi');
        $this->forge->addForeignKey('diskusi_id', 'diskusi', 'id', 'CASCADE', 'CASCADE', 'fk_diskusi_id_tagsdiskusi');
        $this->forge->createTable('tags_diskusi');
    }

    public function down()
    {
        $this->forge->dropForeignKey('tags_diskusi', 'fk_tags_id_tagsdiskusi');
        $this->forge->dropForeignKey('tags_diskusi', 'fk_diskusi_id_tagsdiskusi');
        $this->forge->dropTable('tags_diskusi');
    }
}
