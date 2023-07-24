<?php

namespace App\Controllers;

use App\Models\BlogsModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class BlogsController extends ResourceController {

    protected $request;
    protected $BlogsModel;
    protected $UsersModel;

    public function __construct() {
        $this->request = service('request');
        $this->BlogsModel = new BlogsModel();
        $this->UsersModel = new UsersModel();
    }

    public function tampilBlogs() {
        $page = intval($this->request->getGet('page'));
        $limit = intval($this->request->getGet('limit'));
        $limit = $limit == 0 ? 10 : $limit;

        if ($page == 0) {
            $page = 1;
        }

        $offset = $limit * ($page - 1);
        $totalRows = $this->BlogsModel->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $blogs = $this->BlogsModel->getBlogs($offset, $limit);
        $totalPage = $blogs == null ? 0 : $totalPage;

        $payload = array_map(function($blog) {
            $blog = (array) $blog;
            $user_id = intval($blog['user_id']);

            $user = $this->UsersModel->getAuthor($user_id);

            $data = [
                ...$blog,
                "author" => $user
            ];
            return $data;
        }, $blogs);

        $data = [
            "status"   => true,
            "message"  => "blogs forum mahasiswa jurusan manajemen informatika",
            "data"     => $payload,
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

    public function tampilBlogsById($id) {
        $blog = (array) $this->BlogsModel->find($id);

        if (!$blog) {
            return $this->failNotFound("Blogs tidak ditemukan!!");
        }

        $user = $this->UsersModel->getAuthor($blog["user_id"])[0];

        return $this->respond([
            "status"  => true,
            "message" => "berhasil membuat blogs",
            "data"    => [
                ...$blog, 
                "author" => $user ? $user : null
            ],
        ], 200);
    }

    public function buatBlogs() {
        $user_id = $this->request->getVar('user_id');
        $title = $this->request->getVar('title');
        $konten = $this->request->getVar('konten');
        $img = $this->request->getFile('gambar');

        $slug = substr(url_title($title ? $title : "", '-', true), 0, 80);
        $imgName = null;

        if ($img) {
            $imgName = $img->getRandomName();
        }
 
        $data = [
            "title"   => $title,
            "konten"  => $konten,
            "slug"    => (string) $slug,
            "user_id" => $user_id,
            "img"     => $imgName,
        ];

        if ($this->BlogsModel->insert($data) && $img) {
            $img->move(ROOTPATH . 'public/uploads/blogs', $imgName);
        }        

        return $this->respond([
            "status"  => true,
            "message" => "berhasil membuat blogs",
        ], 201);
    }

    public function editBlogs($id) {
        $blog = (array) $this->BlogsModel->find($id);

        if (!$blog) {
            return $this->failNotFound("Blogs tidak ditemukan!!");
        }

        $user_id = $this->request->getVar('user_id');
        $title = $this->request->getVar('title');
        $konten = $this->request->getVar('konten');
        $img = $this->request->getFile('gambar');

        $slug = substr(url_title($title ? $title : "", '-', true), 0, 80);

        $user = $this->UsersModel->find($user_id);

        return $this->respond([
            "status"  => true,
            "message" => "berhasil membuat blogs",
            "data"    => [
                ...$blog, 
                "author" => $user ? $user : null
            ],
        ], 200);
    }

    public function deleteBlogs($id) {
        $blog = $this->BlogsModel->find($id);

        if (!$blog) {
            return $this->failNotFound("Blogs tidak ditemukan!!");
        }

        $this->BlogsModel->delete(["id" => $id]);

        return $this->respond([
            "status"  => true,
            "message" => "berhasil hapus blogs " . $blog->title,
        ], 200);
    }
}
