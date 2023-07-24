<?php

namespace App\Controllers;
use App\Models\PengumumanModel;

use CodeIgniter\RESTful\ResourceController;

class PengumumanController extends ResourceController {

    protected $request;
    protected $PengumumanModel;

    public function __construct() {
        $this->request = service('request');
        $this->PengumumanModel = new PengumumanModel();
    }

    public function tampilPengumuman() {
        $page = intval($this->request->getGet('page'));
        $limit = intval($this->request->getGet('limit'));
        $limit = $limit == 0 ? 10 : $limit;

        if ($page == 0) { $page = 1; }
 
        $offset = $limit * ($page - 1);
        $totalRows = $this->PengumumanModel->countAllResults();
        $totalPage = ceil($totalRows / $limit);

        $pengumuman = $this->PengumumanModel->getPengumuman($offset, $limit);
        $totalPage = $pengumuman == null ? 0 : $totalPage;

        $data = [
            "status"   => true,
            "message"  => "pengumuman forum mahasiswa jurusan manajemen informatika",
            "data"     => $pengumuman,
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

    public function buatPengumuman() {
        $title = $this->request->getVar('title');
        $konten = $this->request->getVar('konten');
        $gambar = $this->request->getFile('gambar');
        $file = $this->request->getFile('file');
        $gambarName = null;
        $fileName = null;

        $slug = substr(url_title($title ? $title : "", '-', true), 0, 80);

        if ($gambar) {
            $gambarName = $gambar->getRandomName();
        }

        if ($file) {
            $fileName = $file->getRandomName();
        } 

        $data = [
            "title"   => $title,
            "konten"  => $konten,
            "slug"    => (string) $slug,
            "gambar"  => $gambarName,
            "file"    => $fileName,
        ];

        // $this->PengumumanModel->insert($data);

        if ($gambar) {
            $gambar->move(ROOTPATH . 'public/uploads', $gambarName);
        }   

        if ($file) {
            $file->move(ROOTPATH . 'public/uploads/dokumen', $fileName);
        }

        return $this->respond([
            "status"  => true,
            "message" => "berhasil membuat pengumuman",
            "data"    => $data
        ], 201);
    }
}
