<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewsModel extends Model
{
    protected $table            = 'views';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'divice_key', 'diskusi_id'];
}
