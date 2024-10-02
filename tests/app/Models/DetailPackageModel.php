<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPackageModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_package';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['package_id','day','activity','activity_type','object_id','description'];

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

    public function get_detailPackage_by_id($package_id)
    {

        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id) 
            ->get();

        return $query;
    }

    public function getCombinedData($package_id)
    {
        $culinaryPlaceModel = new CulinaryPlaceModel();
        $souvenirPlaceModel = new SouvenirPlaceModel();
        $worshipPlaceModel = new WorshipPlaceModel();
        $homestayModel = new HomestayModel();
        $facilityModel = new FacilityModel();
        $attractionModel = new AttractionModel();
        $eventModel = new EventModel();

        $culinaryData = $culinaryPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=culinary_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $souvenirData = $souvenirPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=souvenir_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $worshipData = $worshipPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=worship_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $homestayData = $homestayModel->select('package_id, day, activity, object_id, activity_type, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=homestay.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $facilityData = $facilityModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng, price, category')
        ->join('detail_package', 'detail_package.object_id=facility.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $attractionData = $attractionModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng, price, category')
        ->join('detail_package', 'detail_package.object_id=attraction.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        $eventData = $eventModel->select('package_id, day, activity, object_id, activity_type, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng, price, category')
        ->join('detail_package', 'detail_package.object_id=event.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        // Gabungkan hasil dari model
        $combinedData1 = array_merge($culinaryData, $souvenirData, $worshipData, $homestayData);

        // Gabungkan hasil dari  model
        $combinedData2 = array_merge($facilityData, $attractionData, $eventData);

        // Tambahkan kolom price dan category dengan nilai default
        foreach ($combinedData1 as &$item) {
            $item['price'] = 0;
            $item['category'] = 2;
        }

        $combinedData = array_merge($combinedData1, $combinedData2);

        usort($combinedData, function($a, $b) {
            $dayComparison = strcmp($a['day'], $b['day']);
            if ($dayComparison === 0) {
                // Jika 'day' sama, bandingkan berdasarkan 'activity'
                return strcmp($a['activity'], $b['activity']);
            }
            // Urutkan berdasarkan 'day' terlebih dahulu
            return $dayComparison;
        });

        return $combinedData;
    }

//   ---  
    public function get_day_by_package($package_id)
    {
        $query = $this->db->table($this->table)
            ->select("day")
            ->where('package_id', $package_id) 
            ->distinct()
            ->get();

        return $query;
    }

    public function get_activity_day($day, $package_id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('package_id', $package_id) 
            ->where('day', $day) 
            ->get();

        return $query;
    }

    public function culinary_place($package_id)
    {
        $culinaryPlaceModel = new CulinaryPlaceModel();

        $culinary_place = $culinaryPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, price, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=culinary_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        return $culinary_place;
    }

    public function worship_place($package_id)
    {
        $worshipPlaceModel = new WorshipPlaceModel();

        $worship_place = $worshipPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=worship_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        return $worship_place;
    }

    public function souvenir_place($package_id)
    {
        $souvenirPlaceModel = new SouvenirPlaceModel();

        $souvenir_place = $souvenirPlaceModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, price, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=souvenir_place.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        return $souvenir_place;
    }

    public function attraction($package_id)
    {
        $attractionModel = new AttractionModel();

        $attraction = $attractionModel->select('package_id, day, activity, activity_type, object_id, detail_package.description, name, price, geom, ST_Y(ST_Centroid(geom)) AS lat, ST_X(ST_Centroid(geom)) AS lng')
        ->join('detail_package', 'detail_package.object_id=attraction.id')
        ->where('detail_package.package_id', $package_id)
        ->get()->getResultArray();

        return $attraction;
    }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('package_id', $requestData['package_id'])
            ->where('day', $requestData['day'])
            ->where('activity', $requestData['activity'])
            ->get()
            ->getRow();
    }

    public function add_new_packageActivity($packageActivity = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($packageActivity);
        return $insert;
    }

    function get_object(){
        $query = $this->db->get('attraction');
        return $query;  
    }

    // public function add_new_detail_service($id, array $detailService)
    // {
    //     // dd($detailService);
    //     $query = false;
    //     foreach ($detailService as $ds) {
    //         $content = [
    //             'service_package_id' => $ds,
    //             'package_id' => $id,
    //             'status' => '1',
    //         ];
    //         $query = $this->db->table($this->table)->insert($content);
    //     }
    //     return $query;
    // }

    // public function delete_detail_service($id = null)
    // {
    //     return $this->db->table($this->table)->delete(['package_id' => $id]);
    // }

}
