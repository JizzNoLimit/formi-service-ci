<?php

namespace App\Models;

use CodeIgniter\Model;

class TagsModel extends Model
{
    protected $table            = 'tags';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'name', 'desk', 'total_tags'];

    function getTags($offset, $limit) {
        $builder = $this->db->table('tags');
        $builder->orderBy('total_tags', 'DESC');
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }
}
