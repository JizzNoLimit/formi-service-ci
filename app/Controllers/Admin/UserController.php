<?php

namespace App\Controllers\Admin;

use App\Models\ProfileModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $Request;
    protected $UserModel;
    protected $ProfileModel;

    public function __construct() {
        $this->Request = service("request");
        $this->UserModel = new UsersModel();
        $this->ProfileModel = new ProfileModel();
    }

    public function tampilUsers() {
        // Show users
        $search = (string) $this->Request->getGet('search_query');
        $page = intval($this->Request->getGet('page'));
        $limit = intval($this->Request->getGet('limit'));
        
        if ($page == 1) { $page = 0; }

        $offset = $limit * $page;

        $totalRows = $this->UserModel->totalData($search);
        $totalPage = ceil($totalRows / $limit);

        $users = $this->ProfileModel->getUser($offset, $limit);
        if ($users === []) { $totalPage = 0; }

        $data = [
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $users,
            "metadata" => [
                "page"      => $page,
                "totalRows" => $totalRows,
                "totalPage" => $totalPage,
                "offset"    => $offset
            ]
        ];
        return $this->respond($data, 200);
    }

    public function tambahUser()
    {
        // Inser data user
        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');
        $role = $this->Request->getVar('role');

        $nim = $this->Request->getVar('nim');
        $first_name = $this->Request->getVar('first_name');
        $last_name = $this->Request->getVar('last_name');

        $user = $this->UserModel->where('username', $username)->orWhere('email', $email)->first();
        if ($user->username === $username) {
            return $this->respond([
                "message" => "username sudah digunakan"
            ], 302);
        } elseif ($user->email === $email) {
            return $this->respond([
                "message" => "email sudah digunakan"
            ], 302);
        }

        $data = [
            "username" => (string) $username,
            "email"    => (string) $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role"     => (string) $role,
        ];
        $this->UserModel->insert($data);

        $userId = $this->UserModel->insertID();

        $profile = [
            "nim"        => (string) $nim,
            "first_name" => (string) $first_name,
            "last_name"  => (string) $last_name,
            "user_id"    => intval($userId)
        ];

        $this->ProfileModel->insert($profile);

        return $this->respondCreated([
            "message" => "berhasil membuat user"
        ]);
    }
}