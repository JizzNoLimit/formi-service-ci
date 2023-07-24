<?php

namespace App\Controllers\Admin;

use App\Models\ProfileModel;
use App\Models\UsersModel;
use App\Models\UserVerificationModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    use ResponseTrait;
    protected $Request;
    protected $UserModel;
    protected $ProfileModel;

    public function __construct() {
        $this->Request = service("request");
        $this->response = service("response");
        $this->UserModel = new UsersModel();
        $this->ProfileModel = new ProfileModel();
    }

    public function tampilUsers() {
        // Show users
        $search = (string) $this->Request->getGet('q');
        $page = intval($this->Request->getGet('page'));
        $limit = intval($this->Request->getGet('limit'));
        
        if ($page == 0) { $page = 1; }

        $limit = $limit != 0 ? $limit : 16;  

        $offset = $limit * ($page - 1);

        $totalRows = $this->UserModel->totalData($search);
        $totalPage = ceil($totalRows / $limit);

        $users = $this->UserModel->getUser($search, $offset, $limit);
        if (!$users) { $totalPage = 0; }

        $data = [
            "status"   => true,
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $users,
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

    public function tampilUsersId($id) {
        $users = $this->UserModel->getUserId($id);
        if ($users == null) {
            return $this->failNotFound("data tidak ditemukan, mohon diperiksa kembali");
        }
        $user = $users[0];

        $data = [
            "status"   => true,
            "message"  => "data detail  forum mahasiswa jurusan manajemen informatika",
            "data"     => $user
        ];
        return $this->respond($data, 200);
    }

    public function tambahUser() {
        // Inser data user
        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');
        $role = $this->Request->getVar('role');
        $role = $role ? $role : "mahasiswa";

        $nim = $this->Request->getVar('nim');
        $first_name = $this->Request->getVar('first_name');
        $last_name = $this->Request->getVar('last_name');
        $alamat = $this->Request->getVar('alamat');


        $user = $this->UserModel->where('username', $username)->orWhere('email', $email)->first();
        if ($user != null && $username === $user->username) {
            return $this->respond([
                "status"  => false,
                "message" => "username ". $username .  " sudah digunakan"
            ], 302);
        } elseif ($user != null && $email === $user->email) {
            return $this->respond([
                "status"  => false,
                "message" => $email .  " sudah terdaftar"
            ], 302);
        }

        $profile = [
            "nim"        => (string) $nim,
            "first_name" => (string) $first_name,
            "last_name"  => (string) $last_name,
            "alamat"     => (string) $alamat,
        ];

        $this->ProfileModel->insert($profile);

        $profileId = $this->ProfileModel->getInsertID();
        
        $data = [
            "username"   => (string) $username,
            "email"      => (string) $email,
            "password"   => password_hash($password, PASSWORD_BCRYPT),
            "role"       => (string) $role,
            "profile_id" => $profileId
        ];

        if (!$this->UserModel->insert($data)) {
            return $this->fail($this->UserModel->errors());
        }

        return $this->respond([
            "status"  => true,
            "message" => "berhasil menambahkan data user"
        ], 201);
    }

    public function editUser($id) {
        # Edit user by id
        $user = $this->UserModel->getUserId($id)[0];

        if (!$user || !$id) {
            return $this->respond([
                "status"  => false,
                "message" => "data tidak ditemukan"
            ], 404);
        }

        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');
        $role = $this->Request->getVar('role');
        $nim = $this->Request->getVar('nim');
        $first_name = $this->Request->getVar('first_name');
        $last_name = $this->Request->getVar('last_name');
        $alamat = $this->Request->getVar('alamat');

        if ($password) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        }

        $data = [
            "username" => (string) !$username ? $user->username : $username,
            "email"    => (string) !$email ? $user->email : $email,
            "password" => (string) !$password ? $user->password : $password,
            "role"     => (string) !$role ? $user->role : $role,
        ];

        $this->UserModel->update($id, $data);

        $profile = [
            "nim"        => (string) !$nim ? $user->nim : $nim,
            "first_name" => (string) !$first_name ? $user->first_name : $first_name,
            "last_name"  => (string) !$last_name ? $user->last_name : $last_name,
            "alamat"     => (string) !$alamat ? $user->alamat : $alamat,
        ];

        $this->ProfileModel->update($user->profile_id, $profile);

        return $this->respond([
            "status"  => true,
            "message" => "data ". $user->username . " berhasil diupdate" 
        ], 201);
    }

    public function hapusUser($id) {
        $user = $this->UserModel->find($id);

        if ($user == null) {
            return $this->respond([
                "status"  => false,
                "message" => "hapus data gagal, user tidak ditemukan"
            ], 404);
        }

        $this->UserModel->delete($id);

        return $this->respond([
            "status"  => true,
            "message" => "hapus data user berhasil",
            "data"    => $user
        ]);
    }

    public function tampilVerification() {
        $UserVerification = new UserVerificationModel();

        $search = (string) $this->Request->getGet('q');
        $page = intval($this->Request->getGet('page'));
        $limit = intval($this->Request->getGet('limit'));
        
        if ($page == 0) { $page = 1; }

        $limit = $limit != 0 ? $limit : 16;  

        $offset = $limit * ($page - 1);

        $totalRows = $UserVerification->totalData($search);
        $totalPage = ceil($totalRows / $limit);

        $users = $UserVerification->getUser($search, $offset, $limit);
        if (!$users) { $totalPage = 0; }

        $data = [
            "status"   => true,
            "message"  => "user forum mahasiswa jurusan manajemen informatika",
            "data"     => $users,
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

    public function verification($id) {
        $userVerification = new UserVerificationModel();

        $userVerif = $userVerification->find($id);

        if (!$userVerification || !$id) {
            return $this->respond([
                "status"  => false,
                "message" => "data tidak ditemukan"
            ], 404);
        }

        $user = $this->UserModel->where('username', $userVerif->username)->orWhere('email', $userVerif->email)->first();

        if ($userVerif->username === $user?->username) {
            return $this->respond([
                "status"  => false,
                "message" => "username: " . $userVerif->username . " sudah digunakan"
            ], 302);
        } elseif ($userVerif->email === $user?->email) {
            return $this->respond([
                "status"  => false,
                "message" => "email: " . $userVerif->email . " sudah digunakan"
            ], 302);
        }

        $profile = [
            "nim"        => (string) $userVerif->nim,
            "first_name" => (string) $userVerif->first_name,
            "last_name"  => (string) $userVerif->last_name,
        ];

        $this->ProfileModel->insert($profile);

        $profileId = $this->ProfileModel->insertID();

        $data = [
            "username"   => (string) $userVerif->username,
            "email"      => (string) $userVerif->email,
            "password"   => password_hash($userVerif->password, PASSWORD_BCRYPT),
            "role"       => (string) $userVerif->role,
            "profile_id" => intval($profileId)
        ];

        if (!$this->UserModel->insert($data)) {
            return $this->fail($this->UserModel->errors());
        }

        $userVerification->update($id, ["status" => true]);

        return $this->respond([
            "status"  => true,
            "message" => "verifikasi data user berhasil",
            "data"    => $user?->username
        ], 202);
    }
}
