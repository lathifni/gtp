<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityUnitModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_unit';
    // protected $primaryKey = 'id';
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


    public function get_list_facility_unit() {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        
        if(empty($lastId)){
            $id = "FU01";
        } else {
            $count = (int)substr($lastId['id'], 2);
            $id = sprintf('FU%02d', $count + 1);
        }

        return $id;
    }

    public function add_new_facilityUnit($requestData)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

    public function get_facility_unit_selected($facility_unit_id) 
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $facility_unit_id)
            ->get();
        return $query;
    }
}
