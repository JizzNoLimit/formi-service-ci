<?php

namespace App\Models;

use CodeIgniter\Model;

class UserVerificationModel extends Model
{
    protected $table            = 'users_verifications';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'username', 'email', 'password', 'role', 'nim', 'first_name', 'last_name', 'status', 'tgl_lahir', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
