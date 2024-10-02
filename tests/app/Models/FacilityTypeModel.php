<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_type';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'type'];

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

    public function get_list_facility_type()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->where('id<>', 'A0005')
            ->get();
        return $query;
    }

    public function get_list_facility_type_pieh()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->where('id', 'A0005')
            ->get();
        return $query;
    }
}
