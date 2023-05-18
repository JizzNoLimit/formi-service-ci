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

    public function getUser($search, $offset, $limit) {
        $builder = $this->db->table('profile');
        $builder->join('users', 'users.id = profile.user_id');
        $builder->like('nim', $search)->orLike('username', $search)->orLike('email', $search);
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }

    public function getUserId($id)
    {
        $builder = $this->db->table('profile');
        $builder->select('profile.id, users.username, users.email, users.password, users.role, nim, first_name, last_name, tgl_lahir, alamat, bio, foto, user_id, created_at, updated_at');
        $builder->join('users', 'users.id = profile.user_id');
        $builder->where('user_id', $id);
        $query = $builder->get();
        return $query->getResult();
    }

    function totalData($search) {
        $builder = $this->db->table('profile');
        $builder->select('users.username, users.email, nim');
        $builder->join('users', 'users.id = profile.user_id');
        $builder->like('nim', $search)->orLike('username', $search)->orLike('email', $search);
        $query = $builder->countAllResults();
        return $query;
    }
}
