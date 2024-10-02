<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryAttractionModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_attraction';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'attraction_id', 'url'];

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

    public function get_gallery($attraction_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->orderBy('id', 'ASC')
            ->where('attraction_id', $attraction_id)
            ->get();
        return $query;
    }

    public function get_gallery2()
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->notLike('attraction_id', 'A0001')
            ->notLike('attraction_id', 'A0005')
            ->notLike('attraction_id', 'A0006')
            ->notLike('attraction_id', 'A0007')
            ->notLike('attraction_id', 'A0008')
            ->notLike('attraction_id', 'A0009')
            ->orderBy('id', 'ASC')
            // ->where('attraction_id', $attraction_id)
            ->get();
        return $query;
    }

    public function get_gallery3()
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->notLike('attraction_id', 'A0001')
            ->notLike('attraction_id', 'A0002')
            ->notLike('attraction_id', 'A0003')
            ->notLike('attraction_id', 'A0004')
            ->notLike('attraction_id', 'A0005')
            ->notLike('attraction_id', 'A0006')
            ->orderBy('id', 'ASC')
            // ->where('attraction_id', $attraction_id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        $count = (int)substr($lastId['id'], 2);
        $id = sprintf('GA%03d', $count + 1);
        return $id;
    }

    public function add_new_gallery($id = null, $data = null)
    {
        $query = false;
        foreach ($data as $gallery) {
            $new_id = $this->get_new_id();
            $content = [
                'id' => $new_id,
                'attraction_id' => $id,
                'url' => $gallery
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
        return $this->db->table($this->table)->delete(['attraction_id' => $id]);
    }
}
