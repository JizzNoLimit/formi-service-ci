<?php

namespace App\Controllers;

use App\Models\DiskusiModel;
use App\Models\TagsDiskusiModel;
use App\Models\TagsModel;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class DiskusiController extends ResourceController
{
    use ResponseTrait;
    protected $request;
    protected $DiskusiModel;
    protected $TagsModel;
    protected $TagsDiskusiModel;
    protected $UsersModel;

    public function __construct() {
        $this->request = service("request");
        $this->DiskusiModel = new DiskusiModel();
        $this->TagsModel = new TagsModel();
        $this->TagsDiskusiModel = new TagsDiskusiModel();
        $this->UsersModel = new UsersModel();
    }

    public function tampilDiskusi() {
        $page = intval($this->request->getGet('page'));
        $limit = intval($this->request->getGet('limit'));
        $limit = $limit == 0 ? 10 : $limit;

        if ($page == 0) { $page = 1; }
 
        $offset = $limit * ($page - 1);
        $totalRows = $this->DiskusiModel->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $diskusi = $this->DiskusiModel->getDiskusi($offset, $limit);
        $totalPage = $diskusi == null ? 0 : $totalPage;
 
        $diskusis = array_map(function($diskusi) {
            $diskusi = (array) $diskusi;
            $id = intval($diskusi['id']);
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
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
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

    public function tampilDiskusiId($id) {
        $diskusi = (array) $this->DiskusiModel->find($id);

        if (!$diskusi) {
            return $this->failNotFound("diskusi tidak ditemukan, mohon diperiksa kembali url anda");
        }

        $tags = $this->TagsDiskusiModel->getTagsName($id);
        $user = $this->UsersModel->getAuthor($diskusi['user_id'])[0];

        $data = [
            "status"   => true,
            "message"  => "data detail  forum mahasiswa jurusan manajemen informatika",
            "data"     => [
                ...$diskusi,
                "tags"   => $tags,
                "author" => $user
            ],
        ];
        return $this->respond($data, 200);
    }

    public function tambahDiskusi() {
        $user_id = $this->request->getVar('user_id');
        $title = $this->request->getVar('title');
        $desk = $this->request->getVar('desk');
        $tags = $this->request->getVar('tags');

        $slug = substr(url_title($title ? $title : "", '-', true), 0, 80);

        $data = [
            "title"   => $title,
            "desk"    => $desk,
            "slug"    => (string) $slug,
            "user_id" => $user_id
        ];

        if (!$this->DiskusiModel->save($data)) {
            return $this->fail($this->DiskusiModel->errors());
        } 

        $data = $tags;

        $this->TagsModel->upsertBatch($data);

        $tags_diskusi = array_map(function($tag) {
            $diskusiId = $this->DiskusiModel->getInsertID();
            ["id" => $tags_id] = (array) $tag;
            $data = [
                "tags_id"    => $tags_id,
                "diskusi_id" => $diskusiId
            ];
            return $data;
        }, $data);
        
         $this->TagsDiskusiModel->upsertBatch($tags_diskusi);

        foreach ($data as $tag) {
            ["id" => $id] = (array) $tag;
            $count = $this->TagsDiskusiModel->where('tags_id', $id)->countAllResults();
            $data = [
                "total_tags" => $count
            ];
            $this->TagsModel->update($id, $data);
        }

        return $this->respond([
            "status"  => true,
            "message" => "berhasil membuat diskusi",
        ], 201);
    }

    function editDiskusi($id) {
        $user_id = $this->request->getGet('user_id');

        $diskusi = $this->DiskusiModel->find($id);

        if ($user_id !== $diskusi->user_id) {
            return $this->respond([
                "status"  => false,
                "message" => "akses tidak diizinkan!!"
            ], 401);
        }
    }
}
