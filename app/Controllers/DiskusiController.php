<?php

namespace App\Controllers;

use App\Models\DiskusiModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class DiskusiController extends ResourceController
{
    use ResponseTrait;
    protected $request;
    protected $DiskusiModel;

    public function __construct() {
        $this->request = service("request");
        $this->DiskusiModel = new DiskusiModel();
    }

    public function tambahDiskusi() {
        $user_id = $this->request->getVar('user_id');
        $title = $this->request->getVar('title');
        $desk = $this->request->getVar('desk');
        $img = $this->request->getFile('img');
        
        $slug = substr(url_title($title ? $title : "", '-', true), 0, 80);

        $img_name = null;

        $validated = $this->validate([
            'img'  => [
                'uploaded[img]',
                'mime_in[img,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[img,2096]',
            ]
        ]);

        if ($validated) {
            $img_name = $img->getRandomName();
            $img->move('uploads/diskusi', $img_name);
        }
        
        $data = [
            "title"   => $title,
            "desk"    => $desk,
            "img"     => $img_name,
            "slug"    => (string) $slug,
            "user_id" => $user_id
        ];

        if (!$this->DiskusiModel->save($data)) {
            return $this->fail($this->DiskusiModel->errors());
        }

        return $this->respond($data, 200);
    }
}
