<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitHomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'unit_homestay';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id','homestay_id','nama_unit','description','price','capacity'];

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

    public function get_unit_homestay_all()
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay', 'unit_homestay.homestay_id=homestay.id')
            ->join('homestay_unit_type', 'unit_homestay.unit_type=homestay_unit_type.id')
            ->orderby('homestay.id', 'ASC')
            ->orderby('homestay_unit_type.id', 'ASC')
            ->get();
        return $query;
    }

    public function get_unit_homestay($homestay_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay_unit_type', 'unit_homestay.unit_type=homestay_unit_type.id')
            ->where('homestay_id', $homestay_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_by_id($homestay_id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->get();

        return $query;
    }
 
    public function get_list_unit_homestay() {
        $query = $this->db->table($this->table)
            ->select("*")
            ->get();

        return $query;
    }

    public function get_unit_homestay_selected($unit_number,$homestay_id, $unit_type) 
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->where('unit_number', $unit_number)
            ->get();
        return $query;
    }
    
    public function get_new_unit_number($id, $type)
    {
        $lastId = $this->db->table($this->table)
            ->select('unit_number')
            ->where('homestay_id', $id)
            ->where('unit_type', $type)
            ->orderBy('unit_number', 'ASC')->get()->getLastRow('array');

        if(empty($lastId)){
            $unit_number='01';
        }else{
            $count = (int)substr($lastId['unit_number'], 1);
            $unit_number = sprintf('%02d', $count + 1);
        }
        return $unit_number;
    }

    public function add_new_unitHomestay($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

    public function delete_unit($array2 = null)
    {
        // dd($array2);
        return $this->db->table($this->table)->delete($array2);
    }

    public function update_unit_homestay($unit_number= null, $homestay_id= null, $unit_type= null, $data = null)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('unit_number', $unit_number)
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->update($data);
        return $query;
    }
}
