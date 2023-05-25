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

    public function tampilDiskusi() {
        $search = (string) $this->request->getGet('q');
        $tab = (string) $this->request->getGet('tab') ? $this->request->getGet('tab') : "populer";
        $page = intval($this->request->getGet('page') ? $this->Request->getGet('page') : 1);
        $limit = intval($this->request->getGet('limit') ? $this->Request->getGet('limit') : 10);

        if ($page == 1) { $page = 0; }
 
        $offset = $limit * $page;

        $totalRows = $this->DiskusiModel->like('title', $search)->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $data = $this->DiskusiModel->findAll();

        $data = [
            "status"   => "ok",
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $data,
            "metadata" => [
                "page"      => $page == 0 ? 1 : $page,
                "totalRows" => $totalRows,
                "totalPage" => $totalPage,
                "offset"    => $offset,
                "limit"     => $limit
            ],
        ];

        return $this->respond($data, 200);
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

        return $this->respond([
            "status"  => "ok",
            "message" => "berhasil membuat diskusi"
        ], 201);
    }
}
