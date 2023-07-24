<?php

namespace App\Controllers;

use App\Models\TagsDiskusiModel;
use App\Models\TagsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class TagsController extends ResourceController {

    protected $request;
    protected $TagsModel;
    protected $TagsDiskusiModel;
    protected $UsersModel;

    public function __construct() {
        $this->request = service('request');
        $this->TagsModel = new TagsModel();
        $this->TagsDiskusiModel = new TagsDiskusiModel();
        $this->UsersModel = new UsersModel();
    }
    
    public function getTags() {
        $page = intval($this->request->getGet('page'));
        $limit = intval($this->request->getGet('limit'));
        $limit = $limit == 0 ? 20 : $limit;

        if ($page == 0) { $page = 1; }
 
        $offset = $limit * ($page - 1);
        $totalRows = $this->TagsModel->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $tags = $this->TagsModel->getTags($offset, $limit);
        $totalPage = $tags == null ? 0 : $totalPage;
 
        $data = [
            "status"   => true,
            "message"  => "tags forum mahasiswa jurusan manajemen informatika",
            "data"     => $tags,
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

    public function getTagsId($id) {
        $page = intval($this->request->getGet('page'));
        $limit = intval($this->request->getGet('limit'));
        $limit = $limit == 0 ? 10 : $limit;

        if ($page == 0) {
            $page = 1;
        }

        $tag = $this->TagsModel->find($id);

        if (!$tag) {
            return $this->failNotFound("tags tidak ditemukan");
        }

        $offset = $limit * ($page - 1);
        $totalRows = $this->TagsDiskusiModel->where('tags_id', $id)->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $diskusi = $this->TagsDiskusiModel->getDiskusiByTags($offset, $limit, $id);
        $totalPage = $diskusi == null ? 0 : $totalPage;

        $diskusis = array_map(function ($diskusi) {
            $diskusi = (array) $diskusi;
            $id = intval($diskusi['diskusi_id']);
            $user_id = intval($diskusi['user_id']);

            $tags = $this->TagsDiskusiModel->getTagsName($id);
            $user = $this->UsersModel->getAuthor($user_id)[0];

            $data = [
                ...$diskusi,
                "tags"   => $tags,
                "author" => $user
            ];
            return $data;
        }, $diskusi);

        $data = [
            "status"   => true,
            "message"  => "tags ". $id ." forum mahasiswa jurusan manajemen informatika",
            "data"     => $diskusis,
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
}
