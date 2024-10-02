<?php

namespace App\Controllers\Api;

use App\Models\CulinaryPlaceModel;
use App\Models\GalleryCulinaryPlaceModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Culinaryplace extends ResourceController
{
    use ResponseTrait;

    protected $culinaryPlaceModel;
    protected $galleryCulinaryPlaceModel;

    public function __construct()
    {
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->galleryCulinaryPlaceModel = new GalleryCulinaryPlaceModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->culinaryPlaceModel->get_list_cp()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Culinary Place"
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
        $cp = $this->culinaryPlaceModel->get_cp_by_id($id)->getRowArray();

        $response = [
            'data' => $cp,
            'status' => 200,
            'message' => [
                "Success display detail information of Culinary Place"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->culinaryPlaceModel->get_cp_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find culinary place by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGCP = $this->galleryCulinaryPlaceModel->delete_gallery($id);
        $deleteCP = $this->culinaryPlaceModel->delete(['id' => $id]);
        if ($deleteCP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Culinary"
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
                $value= 'CP00'.$h;
            } elseif ($h > 9) {
                $value= 'CP0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->culinaryPlaceModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->culinaryPlaceModel->get_geoJson($value)->getRowArray()['geoJson']);
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
