<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'username', 'email', 'role', 'password'];

    function totalData($search) {
        # code...
        $builder = $this->db->table('users');
        $builder->like('username', $search)->orlike('email', $search);
        $query = $builder->countAllResults();
        return $query;
    }
}
