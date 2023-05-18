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
        $limit = intval($this->Request->getGet('limit') != null ? $this->Request->getGet('limit') : 10);
        
        if ($page == 1) { $page = 0; }

        $offset = $limit * $page;

        $totalRows = $this->ProfileModel->totalData($search);
        $totalPage = ceil($totalRows / $limit);

        $users = $this->ProfileModel->getUser($search, $offset, $limit);
        if ($users === []) { $totalPage = 0; }

        $data = [
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $users,
            "metadata" => [
                "page"      => $page == 0 ? 1 : $page,
                "totalRows" => $totalRows,
                "totalPage" => $totalPage,
                "offset"    => $offset,
                "limit"     => $limit
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
        if ($username === $user->username) {
            return $this->respond([
                "message" => "username sudah digunakan"
            ], 302);
        } elseif ($email === $user->email) {
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
            "message" => "berhasil membuat user " . $username
        ]);
    }

    public function editUser($id) {
        # Edit user by id
        $user = $this->ProfileModel->getUserId($id)[0];

        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');
        $role = $this->Request->getVar('role');
        $nim = $this->Request->getVar('nim');
        $first_name = $this->Request->getVar('first_name');
        $last_name = $this->Request->getVar('last_name');

        if ($username === $user->username) {
            return $this->respond([
                "message" => "username: ". $username ."sudah digunakan"
            ], 302);
        } elseif ($email === $user->email) {
            return $this->respond([
                "message" => "email: " . $email . " sudah digunakan"
            ], 302);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            "username" => (string) $username != null ? $username : $user->username,
            "email"    => (string) $email != null ? $email : $user->email,
            "password" => (string) $password != null ? $hash : $user->password,
            "role"     => (string) $role != null ? $role : $user->role,
        ];

        $this->UserModel->update($id, $data);

        $profile = [
            "nim"        => (string) $nim != null ? $nim : $user->nim,
            "first_name" => (string) $first_name != null ? $first_name : $user->first_name,
            "last_name"  => (string) $last_name != null ? $last_name : $user->last_name,
        ];

        $this->ProfileModel->update($user->id, $profile);

        return $this->respond([
            "message" => "data ". $user->username . " berhasil diupdate" 
        ], 201);
    }
}
