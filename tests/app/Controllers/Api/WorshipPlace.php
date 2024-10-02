<?php

namespace App\Controllers\Api;

use App\Models\WorshipPlaceModel;
use App\Models\GalleryWorshipPlaceModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Worshipplace extends ResourceController
{
    use ResponseTrait;

    protected $worshipPlaceModel;
    protected $galleryWorshipPlaceModel;

    public function __construct()
    {
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->galleryWorshipPlaceModel = new GalleryWorshipPlaceModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->worshipPlaceModel->get_list_wp()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Worship Place"
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
        $cp = $this->worshipPlaceModel->get_wp_by_id($id)->getRowArray();

        $response = [
            'data' => $cp,
            'status' => 200,
            'message' => [
                "Success display detail information of Worship Place"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->worshipPlaceModel->get_wp_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find worship place by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGWP = $this->galleryWorshipPlaceModel->delete_gallery($id);
        $deleteWP = $this->worshipPlaceModel->delete(['id' => $id]);
        if ($deleteWP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete worship"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }


    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<20; $h++){
            if ($h < 10) {
                $value= 'WP00'.$h;
            } elseif ($h > 9) {
                $value= 'WP0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->worshipPlaceModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->worshipPlaceModel->get_geoJson($value)->getRowArray()['geoJson']);
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
