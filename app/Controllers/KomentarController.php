<?php

namespace App\Controllers;

use App\Models\DiskusiModel;
use App\Models\KomentModel;
use CodeIgniter\RESTful\ResourceController;

class KomentarController extends ResourceController
{
    protected $request;
    protected $KomentModel;

    public function __construct()
    {
        $this->request = service("request");
        $this->KomentModel = new KomentModel();
    }

    public function tampilKoment($id) {
        $komens = $this->KomentModel->getKomentDiskusiId($id);

        $data = array_map(function($komen) {
            $array = (array) $komen;
            $test = $this->KomentModel->getReply($komen->id);
            $data = [
                ...$array,
                "reply" => $test
            ];
            return $data;
        }, $komens);

        $data = [
            "status"   => true,
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $data,
        ];

        return $this->respond($data, 200);
    }

    public function buatKoment() {
        $DiskusiModel = new DiskusiModel();

        $konten = $this->request->getVar('konten');
        $user_id = $this->request->getVar('user_id');
        $diskusi_id = $this->request->getVar('diskusi_id');
        $parent_id = $this->request->getVar('parent_id');

        $data = [
            "konten"     => $konten,
            "user_id"    => $user_id,
            "diskusi_id" => $diskusi_id,
            "parent_id"  => $parent_id,
        ];

        if (!$this->KomentModel->save($data)) {
            return $this->fail($this->KomentModel->errors());
        }

        $count = $this->KomentModel->where('diskusi_id', $diskusi_id)->countAllResults();

        $dataUpdate = [
            "total_komentar" => $count
        ];

        $DiskusiModel->update($diskusi_id, $dataUpdate);

        return $this->respond([
            "status"  => true,
            "message" => "berhasil mengirimkan komentar",
        ], 201);
    }
}
