<?php

namespace App\Models;

use CodeIgniter\Model;

class HomestayUnitTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'homestay_unit_type';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name_type'];

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
            ->select('id, name_type')
            ->orderBy('name_type', 'ASC')
            ->get();
        return $query;
    }
}