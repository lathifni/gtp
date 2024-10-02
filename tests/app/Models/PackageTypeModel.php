<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'package_type';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'type_name'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function get_list_type() {
        $query = $this->db->table($this->table)
            ->select('id, type_name')
            ->orderBy('type_name', 'ASC')
            ->get();
        return $query;
    }
}