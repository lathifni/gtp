<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryUnitModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_unit';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'homestay_id', 'unit_number', 'unit_type', 'url'];

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

    public function get_gallery($homestay_id)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            // ->where('unit_number', $unit_number)
            ->where('homestay_id', $homestay_id)
            // ->where('unit_type', $unit_type)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        
        if(empty($lastId)){
            $id = "GU001";
        } else {
            $count = (int)substr($lastId['id'], 2);
            $id = sprintf('GU%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_gallery($unit_number = null, $homestay_id = null, $unit_type = null, $data = null)
    {
        $query = false;
        foreach ($data as $gallery) {
            $new_id = $this->get_new_id();
            $content = [
                'id' => $new_id,
                'unit_number' => $unit_number,
                'homestay_id' => $homestay_id,
                'unit_type' => $unit_type,
                'url' => $gallery
            ];
            $query = $this->db->table($this->table)->insert($content);
        }
        return $query;
    }

    public function isGalleryExist($id)
    {
        return $this->table($this->table)
            ->where('homestay_id', $id)
            ->get()
            ->getRow();
    }


    public function update_gallery($id = null, $data = null)
    {
        $queryDel = $this->delete_gallery($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        $queryIns = $this->add_new_gallery($id, $data);
        return $queryDel && $queryIns;
    }

    public function delete_gallery($id = null)
    {
        return $this->db->table($this->table)->delete(['homestay_id' => $id]);
    }
}
