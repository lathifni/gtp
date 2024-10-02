<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\GtpModel;
use App\Models\VillageModel;
use App\Models\KecamatanModel;
use App\Models\KabkotaModel;
use App\Models\ProvinsiModel;
use App\Models\AttractionModel;
use CodeIgniter\API\ResponseTrait;

class Village extends BaseController
{
    use ResponseTrait;
    protected $gtpModel;
    protected $villageModel;
    protected $kecamatanModel;
    protected $kabkotaModel;
    protected $provinsiModel;
    protected $attractionModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->villageModel = new VillageModel();
        $this->kecamatanModel = new KecamatanModel();
        $this->kabkotaModel = new KabkotaModel();
        $this->provinsiModel = new ProvinsiModel();
        $this->attractionModel = new AttractionModel();
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($i=1; $i<20; $i++){
            if ($i < 10) {
                $digitasiValue = 'K0'.$i;
            } elseif ($i > 9) {
                $digitasiValue = 'K'.$i;
            }
            
            if ($digitasi == $digitasiValue) {
                $digiProperty = $this->kabkotaModel->get_wilayah($digitasiValue)->getRowArray();
                $geoJson = json_decode($this->kabkotaModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
            } 
        }

        for($k=1; $k<23; $k++){
            if ($k < 10) {
                $valueKec= 'C0'.$k;
            } elseif ($i > 9) {
                $valueKec= 'C'.$k;
            }

            if ($digitasi == $valueKec) {
                $digiProperty = $this->kecamatanModel->get_wilayah($valueKec)->getRowArray();
                $geoJson = json_decode($this->kecamatanModel->get_geoJson($valueKec)->getRowArray()['geoJson']);
            } 
        }

        for($d=1; $d<9; $d++){
            if ($d < 9) {
                $valueDesa= 'V0'.$d;
            } 
            if ($digitasi == $valueDesa) {
                $digiProperty = $this->villageModel->get_wilayah($valueDesa)->getRowArray();
                $geoJson = json_decode($this->villageModel->get_geoJson($valueDesa)->getRowArray()['geoJson']);
            } 
        }

        if ($digitasi == 'GTP01') {
            $digiProperty = $this->gtpModel->get_desa_wisata()->getRowArray();
            $geoJson = json_decode($this->gtpModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } elseif ($digitasi == 'A0001') {
            $digiProperty = $this->attractionModel->get_tracking()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } elseif ($digitasi == 'A0004') {
            $digiProperty = $this->attractionModel->get_estuaria()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        }  elseif ($digitasi == 'A0005') {
            $digiProperty = $this->attractionModel->get_pieh()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } elseif ($digitasi == 'A0006') {
            $digiProperty = $this->attractionModel->get_makam()->getRowArray();
            $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        } 
        // else {
        //     $digiProperty = $this->attractionModel->get_list_attraction()->getRowArray();
        //     $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
        // }
        

        $content = [
            'type' => 'Feature',
            'geometry' => $geoJson,
            'properties' => [
                'id' => $digiProperty['id'],
                'name' => $digiProperty['name'],
                'lat' => $digiProperty['lat'],
                'lng' => $digiProperty['lng'],
            ]
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];
        return $this->respond($response);
    }




//     public function getDataKK()
// {
//     $request = $this->request->getPost();
//     $digitasiArray = $request['digitasi']; // Mungkin dalam bentuk array

//     $content = [];
//     $response = [
//         'data' => $content,
//         'status' => 200,
//         'message' => ["Success"]
//     ];

//     foreach ($digitasiArray as $digitasi) {
//         $digiProperty = [];
//         $geoJson = [];

//         if ($digitasi == 'GTP01') {
//             $digiProperty = $this->gtpModel->get_desa_wisata()->getRowArray();
//             $geoJson = json_decode($this->gtpModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
//         } elseif ($digitasi == 'V0001') {
//             $digiProperty = $this->villageModel->get_ulakan()->getRowArray();
//             $geoJson = json_decode($this->villageModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
//         } elseif ($digitasi == 'A0001') {
//             $digiProperty = $this->attractionModel->get_tracking()->getRowArray();
//             $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
//         } else {
//             $digiProperty = $this->attractionModel->get_list_attraction()->getRowArray();
//             $geoJson = json_decode($this->attractionModel->get_geoJson($digitasi)->getRowArray()['geoJson']);
//         }

//         $content[] = [
//             'type' => 'Feature',
//             'geometry' => $geoJson,
//             'properties' => [
//                 'id' => $digiProperty['id'],
//                 'name' => $digiProperty['name'],
//                 'lat' => $digiProperty['lat'],
//                 'lng' => $digiProperty['lng'],
//             ]
//         ];
//     }

//     return $this->respond($response);
// }
}
