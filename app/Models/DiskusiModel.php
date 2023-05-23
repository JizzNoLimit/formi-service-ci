<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskusiModel extends Model
{
    protected $table            = 'diskusi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id', 'title', 'slug', 'desk', 'user_id', 'created_at', 'updated_at'
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
}
