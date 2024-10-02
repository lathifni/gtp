<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageDayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'package_day';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['package_id','day','description'];

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

    public function get_list_package_day($id) {
        
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $id)
            ->orderBy('package_id', 'ASC')
            ->get();

        return $query;
    }

    // public function get_listpackageDay_by_id(array $list_day)
    // {
    //     foreach ($list_day as  $key){
    //         $query = $this->db->table($this->table)
    //             ->select("*")
    //             ->where('package_id', $key)
    //             ->get();
    //     }
    //     return $query;
    // }

    public function get_day_by_id($id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $id)
            ->get();
        return $query;
    }

    public function get_day_by_package($package_id)
    {
        $query = $this->db->table($this->table)
            ->select("day")
            ->where('package_id', $package_id) 
            ->distinct()
            ->get();

        return $query;
    }
    
    public function get_package_day_by_id($package_id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id)
            ->get();
        return $query;
    }
    
    // public function get_new_id()
    // {
    //     $lastId = $this->db->table($this->table)->select('package_id')->orderBy('package_id', 'ASC')->get()->getLastRow('array');
    //     $count = (int)substr($lastId['id'], 1);
    //     $id = sprintf('P%4d', $count + 1);
    //     return $id;
    // }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('day', $requestData['day'])
            ->where('package_id', $requestData['package_id'])
            ->get()
            ->getRow();
    }
    
    public function add_new_packageDay($packageDay = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($packageDay);
        return $insert;
    }

    // public function update_packageDay($id = null, $packageDay = null)
    // {
    //     foreach ($packageDay as $key => $value) {
    //         if (empty($value)) {
    //             unset($packageDay[$key]);
    //         }
    //     }
    //     $query = $this->db->table($this->table)
    //         ->where('package_id', $id)
    //         ->update($packageDay);
    //     return $query;
    // }


}
