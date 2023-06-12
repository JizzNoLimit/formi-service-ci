<?php

namespace App\Models;

use CodeIgniter\Model;

class TagsDiskusiModel extends Model
{
    protected $table            = 'tags_diskusi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'tags_id', 'diskusi_id'];

    public function getTagsName($id) {
        $builder = $this->db->table('tags_diskusi');
        $builder->select('tags.id AS id, tags.name AS name');
        $builder->join('tags', 'tags.id = tags_diskusi.tags_id');
        $builder->where('diskusi_id', $id);
        $query = $builder->get();
        return $query->getResult();
    }
}
