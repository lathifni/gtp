<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryPackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_package';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'package_id', 'url'];

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

    public function get_gallery($package_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->where('package_id', $package_id)
            ->orderBy('id', 'ASC')
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 2);
        $id = sprintf('GP%03d', $count + 1);
        return $id;
    }

    public function add_new_gallery($id = null, $data = null)
    {
        $query = false;
        foreach ($data as $gallery) {
            $new_id = $this->get_new_id();
            $content = [
                'id' => $new_id,
                'package_id' => $id,
                'url' => $gallery,
            ];
            $query = $this->db->table($this->table)->insert($content);
        }
        return $query;
    }

    public function isGalleryExist($packageId)
    {
        return $this->table($this->table)
            ->where('package_id', $packageId)
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
        return $this->db->table($this->table)->delete(['package_id' => $id]);
    }

}
