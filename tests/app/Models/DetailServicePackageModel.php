<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailServicePackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_service_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['service_package_id', 'package_id','status'];

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

    public function get_service($package_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('service_package_id')
            ->where('package_id', $package_id)
            ->orderBy('service_package_id', 'ASC')
            ->get();
        return $query;
    }

    public function get_service_package_detail_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->get();
        return $query;
    }

    public function get_service_include_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->where('detail_service_package.status', '1')
            ->get();

        return $query;
    }
    
    public function get_service_exclude_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->join('service_package', 'detail_service_package.service_package_id = service_package.id')
            ->where('detail_service_package.package_id', $id)
            ->where('detail_service_package.status', '0')
            ->get();

        return $query;
    }
    
    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('service_package_id', $requestData['service_package_id'])
            ->where('package_id', $requestData['package_id'])
            ->get()
            ->getRow();
    }
    
    public function add_new_detail_service_package($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

    public function add_new_detail_service($id, $requestData)
    {
        $query = false;
        $content = [
            'service_package_id' =>  $requestData['service_package_id'],
            'package_id' => $id,
            'status' => $requestData['status'],
        ];

        $query = $this->db->table($this->table)->insert($content);
        
        return $query;
    }

    public function delete_detail_service($id = null)
    {
        return $this->db->table($this->table)->delete(['package_id' => $id]);
    }

}
