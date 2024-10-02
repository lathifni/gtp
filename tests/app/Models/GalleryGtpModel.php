<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryGtpModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_gtp';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'gtp_id', 'url'];

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

    public function get_gallery($gtp_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->orderBy('id', 'ASC')
            ->where('gtp_id', $gtp_id)
            ->get();
        return $query;
    }

    public function get_all_gallery()
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->orderBy('id', 'ASC')
            ->get();
        return $query;
    }

    public function add_new_gallery($id = null, $data = null)
    {
        $query = false;
        foreach ($data as $gallery) {
            $content = [
                'gtp_id' => $id,
                'url' => $gallery,
            ];
            $query = $this->db->table($this->table)->insert($content);
        }
        return $query;
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
        return $this->db->table($this->table)->delete(['gtp_id' => $id]);
    }
}
