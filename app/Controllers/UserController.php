<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\IncomingRequest;

class UserController extends ResourceController
{

    protected $Request;

    public function __construct(){
        $this->Request = service("request");
    }
    
    public function createUser()
    {
        //
        $data = [
            'name' => $this->Request->getVar('name'),
            'query' => (int) $this->Request->getGet('id')
        ];
        return $this->respond($data, 200);
    }

    
}
