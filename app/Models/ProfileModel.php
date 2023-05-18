<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table            = 'profile';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id', 'nim', 'first_name', 'last_name', 'tgl_lahir', 'alamat', 'bio', 'foto', 'user_id',
        'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUser($offset, $limit) {
        $builder = $this->db->table('profile');
        $builder->join('users', 'users.id = profile.user_id');
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }
}
