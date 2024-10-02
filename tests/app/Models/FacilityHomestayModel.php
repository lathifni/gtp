<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityHomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_homestay';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'name'];

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

    public function get_list_facility_homestay() {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 2);
        $id = sprintf('FH%02d', $count + 1);
        return $id;
    }

    public function add_new_facilityHomestay($facilityHomestay)
    {
        $insert = $this->db->table($this->table)
            ->insert($facilityHomestay);
        return $insert;
    }

}
