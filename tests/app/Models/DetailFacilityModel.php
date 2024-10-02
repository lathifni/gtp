<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailFacilityModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_facility';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['facility_id', 'attraction_id', 'lat', 'long'];

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

    public function get_facility_by_track($data = null)
    {
        $x = (string)$data['ftype'];
        $columns = "{$this->table}.facility_id,{$this->table}.attraction_id,{$this->table}.lat,{$this->table}.long";
        $query = $this->db->table($this->table)
            ->select("{$columns}")
            ->where('attraction_id', 'A0001')
            ->join('facility', 'detail_facility.facility_id = facility.id')
            ->where('facility.type_id', $x)
            ->select('facility.name, facility.type_id')
            ->get();
        return $query;
    }
}
