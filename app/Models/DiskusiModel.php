<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskusiModel extends Model
{
    protected $table            = 'diskusi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id', 'title', 'slug', 'desk', 'total_koment', 'user_id', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required',
        'desk'  => 'required'
    ];

    protected $validationMessages = [
        'title' => [
            'required'    => 'Judul tidak boleh kosong'
        ],
        'desk'    => [
            'required'    => 'Isi diskusi tidak boleh kosong'
        ]
    ];

    function getDiskusi($offset, $limit) {
        $builder = $this->db->table('diskusi');
        $builder->select('diskusi.id, title, slug, desk, pengunjung, username AS author, created_at, updated_at');
        $builder->join('users', 'users.id = diskusi.user_id');
        $builder->orderBy('created_at', 'DESC');
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }

    function searchDiskusi($search, $limit, $offset) {
        $sql = "SELECT * FROM diskusi WHERE MATCH (title, desk) AGAINST (:search:) LIMIT :limit: OFFSET :offset:";
        $result = $this->db->query($sql, [
            "search" => $search,
            "limit"  => $limit,
            "offset" => $offset
        ]);
        return $result->getResultArray();
    }
}
