<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProfileModel;
use App\Models\UsersModel;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    protected $Request;

    public function __construct() {
        $this->Request = service("request");
        // $this->ProfileModel = new ProfileModel();
    }

    public function login() {
        $userModel = new UsersModel();

        $username = $this->Request->getVar('username');
        $email = $this->Request->getVar('email');
        $password = $this->Request->getVar('password');

        if (!$username && !$email) {
            return $this->failNotFound();
        }

        $user = $username ? $userModel->where('username', $username)->first() : $userModel->where('email', $email)->first();;
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
}
