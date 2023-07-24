<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'username', 'email', 'role', 'password', 'profile_id'];

    protected $validationRules = [
        'username' => 'required',
        'email'    => 'required|valid_email'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Silahkan masukan username'
        ],
        'email'    => [
            'required'    => 'Silahkan masukan email',
            'valid_email' => 'Email tidak valid'
        ]
    ];

    public function getUser($search, $offset, $limit) {
        $builder = $this->db->table('users');
        $builder->select('users.id, username, email, role, profile_id, profile.nim, profile.first_name, profile.last_name, profile.tgl_lahir, profile.alamat, profile.bio, profile.foto, profile.created_at, profile.updated_at');
        $builder->join('profile', 'profile.id = users.profile_id');
        $builder->like('nim', $search)->orLike('username', $search)->orLike('email', $search)->orLike('first_name', $search)->orLike('last_name', $search);
        $query = $builder->get($limit, $offset);
        return $query->getResult();
    }

    public function getUserId($id) {
        $builder = $this->db->table('users');
        $builder->select('users.id, username, email, password, role, profile_id, profile.nim, profile.first_name, profile.last_name, profile.tgl_lahir, profile.alamat, profile.bio, profile.foto, profile.created_at, profile.updated_at');
        $builder->join('profile', 'profile.id = users.profile_id');
        $builder->where('users.id', $id);
        $query = $builder->get();
        return $query->getResult();
    }

    function totalData($search) {
        $builder = $this->db->table('users');
        $builder->join('profile', 'profile.id = users.profile_id');
        $builder->like('nim', $search)->orLike('username', $search)->orLike('email', $search);
        $query = $builder->countAllResults();
        return $query;
    }

    function getAuthor($id) {
        $builder = $this->db->table('users');
        $builder->select('users.id, username, email, role, profile.nim, profile.foto');
        $builder->join('profile', 'profile.id = users.profile_id');
        $builder->where('users.id', $id);
        $query = $builder->get();
        return $query->getResult();
    }
}
