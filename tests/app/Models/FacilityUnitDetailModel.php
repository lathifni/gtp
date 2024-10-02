<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityUnitDetailModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_unit_detail';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['facility_unit_id','unit_homestay_id','description'];

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

    public function get_facility_unit_detail($homestay_id=null, $unit_type=null, $unit_number=null)
    {
        // dd($unit_number);
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('facility_unit', 'facility_unit_detail.facility_unit_id = facility_unit.id')
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->where('unit_number', $unit_number)
            ->get();
            
        return $query;
    }

    public function get_data_facility_unit_detail($unit_number=null, $homestay_id=null, $unit_type=null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('facility_unit', 'facility_unit_detail.facility_unit_id = facility_unit.id')
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->where('unit_number', $unit_number)
            ->get();
            
        return $query;
    }

    public function add_new_facilityUnitDetail($id, $requestData)
    {
        $query = false;

        $content = [
            'facility_unit_id' => $requestData['facility_unit_id'],
            'homestay_id' => $requestData['homestay_id'],
            'unit_type' => $requestData['unit_type'],
            'unit_number' => $requestData['unit_number'],
            'description' => $requestData['description']
        ];

        // $insert = $this->db->table($this->table)
        //     ->insert($content);
        // return $insert;

        $existingRecord = $this->db->table($this->table)
        ->where('facility_unit_id', $requestData['facility_unit_id'])
        ->where('homestay_id', $requestData['homestay_id'])
        ->where('unit_type', $requestData['unit_type'])
        ->where('unit_number', $requestData['unit_number'])
        ->get()
        ->getRow();

        if ($existingRecord) {
            // Data sudah ada, lakukan operasi update
            $insert = $this->db->table($this->table)
                ->where('facility_unit_id', $requestData['facility_unit_id'])
                ->where('homestay_id', $requestData['homestay_id'])
                ->where('unit_type', $requestData['unit_type'])
                ->where('unit_number', $requestData['unit_number'])
                ->update($content);

            return $insert; // Sukses mengupdate data
        } else {
            // Data belum ada, lakukan operasi insert
            $insert =$this->db->table($this->table)->insert($content);

            return $insert; // Sukses menambahkan data
        }

    }

}
