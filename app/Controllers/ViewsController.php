<?php

namespace App\Controllers;

use App\Models\DiskusiModel;
use App\Models\ViewsModel;
use CodeIgniter\RESTful\ResourceController;

class ViewsController extends ResourceController {

    protected $request;
    protected $ViewsModel;
    protected $DiskusiModel;

    public function __construct()
    {
        $this->request = service("request");
        $this->ViewsModel = new ViewsModel();
        $this->DiskusiModel = new DiskusiModel();
    }

    public function views() {
        $id = $this->request->getGet('id');
        $diskusi_id = $this->request->getGet('diskusi_id');

        $diskusi = $this->DiskusiModel->find($diskusi_id);

        if (!$diskusi) {
            return $this->failNotFound("diskusi tidak ditemukan");
        }

        $views = $this->ViewsModel->where(['divice_key' => $id, 'diskusi_id' => $diskusi_id])->countAllResults();

        if ($views > 0) {
            return $this->respond([
                "status"  => true,
                "message" => "viewers sudah melihat diskusi",
            ], 200);
        } else {
            $data = [
                "divice_key" => $id,
                "diskusi_id" => $diskusi_id
            ];

            $this->ViewsModel->insert($data);

            $count = $this->ViewsModel->where('diskusi_id', $diskusi_id)->countAllResults();

            $data = [
                "views" => $count
            ];

            $this->DiskusiModel->update($diskusi_id, $data);

            return $this->respond([
                "status"  => true,
                "message" => "viewers ditambahkan ke daftar viewes",
            ], 200);
        }
        // return $this->respond([
        //     "status"  => true,
        //     "message" => $views,
        //     "device"  => $id
        // ], 200);
    }
}
