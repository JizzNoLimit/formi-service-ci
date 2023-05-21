<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProfileModel;
use App\Models\UsersModel;
use App\Models\UserVerificationModel;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    protected $Request;

    public function __construct() {
        $this->Request = service("request");
        $this->UserModel = new UsersModel();
    }

    public function login() {
        // $userModel = new UsersModel();

        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');

        if (!$username && !$email) {
            return $this->failNotFound();
        }

        $user = $username ? $this->UserModel->where('username', $username)->first() : $this->UserModel->where('email', $email)->first();
        if ($user == null) {
            return $this->respond([
                "status"   => "error",
                "message"  => [
                    "error"  => $username ? "username tidak ditemukan" : "email tidak ditemukan"
                ]
            ], 404);
        }

        $match = password_verify($password, $user->password);
        if (!$match) {
            return $this->respond([
                "status"   => "error",
                "message"  => [
                    "error"  => "password salah"
                ]
            ], 401);
        }

        $jwt_secret = getenv('JWT_SECRET');

        $payload = [
            "id"       => $user->id,
            "username" => $user->username,
            "email"    => $user->email,
            "role"     => $user->role
        ];

        $token = JWT::encode($payload, $jwt_secret, 'HS256', '7d');

        $data = [
            "status"   => "ok",
            "message"  => "login berhasil",
            "data"     => [
                "id"       => $user->id,
                "username" => $user->username,
                "email"    => $user->email,
                "role"     => $user->role,
                "token"    => $token
            ]
        ];
        return $this->respond($data, 200);
    }

    public function register() {
        $userVerification = new UserVerificationModel();

        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');
        $role = $this->Request->getVar('role');
        $nim = $this->Request->getVar('nim');
        $first_name = $this->Request->getVar('first_name');
        $last_name = $this->Request->getVar('last_name');
        $tgl_lahir = $this->Request->getVar('tgl_lahir');

        if (!$username || !$email || !$password || !$nim) {
            return $this->respond([
                "status"  => "error",
                "message" => "data tidak lengkap untuk registrasi"
            ], 404);
        }

        $user = $username ? $this->UserModel->where('username', $username)->first() : $this->UserModel->where('email', $email)->first();;

        if ($username === $user?->username) {
            return $this->respond([
                "status"  => "conflict",
                "message" => "username: " . $username . " sudah digunakan"
            ], 302);
        } elseif ($email === $user?->email) {
            return $this->respond([
                "status"  => "conflict",
                "message" => "email: " . $email . " sudah digunakan"
            ], 302);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            "username"      => (string) $username,
            "email"         => (string) $email,
            "password"      => (string) $hash,
            "nim"           => (string) $nim,
            "first_name"    => (string) $first_name,
            "last_name"     => (string) $last_name,
            "tgl_lahir"     => intval($tgl_lahir),
        ];

        $userVerification->insert($data);

        return $this->respond([
            "status"  => "ok",
            "message" => "Registrasi berhasil",
        ], 201);
    }
}
