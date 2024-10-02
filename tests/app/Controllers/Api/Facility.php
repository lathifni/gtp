<?php

namespace App\Controllers\Api;

use App\Models\FacilityModel;
use App\Models\GalleryFacilityModel;
use App\Models\DetailFacilityModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Facility extends ResourceController
{
    use ResponseTrait;

    protected $facilityModel;
    protected $galleryFacilityModel;
    protected $detailFacilityModel;

    public function __construct()
    {
        $this->facilityModel = new FacilityModel();
        $this->galleryFacilityModel = new GalleryFacilityModel();
        $this->detailFacilityModel = new DetailFacilityModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->facilityModel->get_list_facility()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Facility"
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $facility = $this->facilityModel->get_facility_by_id($id)->getRowArray();

        $list_gallery = $this->galleryFacilityModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }

        $facility['gallery'] = $galleries;

        $response = [
            'data' => $facility,
            'status' => 200,
            'message' => [
                "Success display detail information of Facility"
            ]
        ];
        return $this->respond($response);
    }

    public function findByTrack()
    {
        $request = $this->request->getPost();
        $contents = $this->detailFacilityModel->get_facility_by_track($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find facility by track"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->facilityModel->get_facility_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find facility by radius"
            ]
        ];

        // dd($findByRadius);
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGFC = $this->galleryFacilityModel->delete(['facility_id' => $id]);
        $deleteFC = $this->facilityModel->delete(['id' => $id]);
        if ($deleteFC) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete facility"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<30; $h++){
            if ($h < 10) {
                $value= 'FC00'.$h;
            } elseif ($h > 9) {
                $value= 'FC0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->facilityModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->facilityModel->get_geoJson($value)->getRowArray()['geoJson']);
            } 
        }
        
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
}
