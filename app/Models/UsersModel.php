<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'username', 'email', 'role', 'password'];

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
    function totalData($search) {
        # code...
        $builder = $this->db->table('users');
        $builder->like('username', $search)->orlike('email', $search);
        $query = $builder->countAllResults();
        return $query;
    }
}
