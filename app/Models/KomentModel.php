<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentModel extends Model
{
    protected $table            = 'koment';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id', 'konten', 'user_id', 'diskusi_id', 'parent_id', 'created_at', 'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'int';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
