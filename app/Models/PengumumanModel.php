<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'title', 'konten', 'slug', 'gambar', 'file', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    function getPengumuman($offset, $limit) {
        $builder = $this->db->table('pengumuman');
        $builder->orderBy("created_at", "DESC");
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }
}
