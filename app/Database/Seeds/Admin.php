<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Admin extends Seeder
{
    public function run()
    {
        $password = password_hash('123', PASSWORD_DEFAULT);
        $data = [
            "username" => "admin",
            "email"    => "admin@gmail.com",
            "password" => $password,
            "role"     => "admin"
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
