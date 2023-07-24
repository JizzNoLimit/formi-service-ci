<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogsModel extends Model
{
    protected $table            = 'blogs';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'title', 'konten', 'slug', 'img', 'user_id', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    function getBlogs($offset, $limit) {
        $builder = $this->db->table('blogs');
        $builder->orderBy('created_at', 'DESC');
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }
}
