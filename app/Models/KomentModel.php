<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentModel extends Model
{
    protected $table = "koment";
    protected $useAutoIncrement = true;
    protected $returnType = "object";
    protected $allowedFields = [
        "id",
        "konten",
        "user_id",
        "diskusi_id",
        "parent_id",
        "created_at",
        "updated_at",
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = "int";
    protected $createdField = "created_at";
    protected $updatedField = "updated_at";

    protected $validationRules = [
        "konten" => "required",
    ];

    protected $validationMessages = [
        "konten" => [
            "required" => "Komentarnya diisi lah!!, ngapai lu?🙄",
        ],
    ];

    function getKomentDiskusiId($id)
    {
        $builder = $this->db->table("koment");
        $builder->select(
            "koment.id, konten, user_id, users.username, users.role, diskusi_id, parent_id, created_at, updated_at"
        );
        $builder->join("users", "users.id = koment.user_id");
        $builder->where([
            "diskusi_id" => $id,
            "parent_id" => null,
        ]);
        $builder->orderBy("created_at", "ASC");
        $query = $builder->get();
        return $query->getResult();
    }

    function getReply($parent_id) {
        $builder = $this->db->table("koment");
        $builder->select(
            "koment.id, konten, user_id, users.username, users.role, diskusi_id, parent_id, created_at, updated_at"
        );
        $builder->join("users", "users.id = koment.user_id");
        $builder->where([
            "parent_id" => $parent_id,
        ]);
        $builder->orderBy("created_at", "ASC");
        $query = $builder->get();
        return $query->getResult();
    }
}
