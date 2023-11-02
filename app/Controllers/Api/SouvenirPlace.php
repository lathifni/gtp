<?php

namespace App\Controllers\Api;

use App\Models\SouvenirPlaceModel;
use App\Models\GallerySouvenirPlaceModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class SouvenirPlace extends ResourceController
{
    use ResponseTrait;

    protected $gallerySouvenirPlaceModel;

    public function __construct()
    {
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->gallerySouvenirPlaceModel = new GallerySouvenirPlaceModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->souvenirPlaceModel->get_list_sp()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Souvenir Place"
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
        $cp = $this->souvenirPlaceModel->get_sp_by_id($id)->getRowArray();

        $response = [
            'data' => $cp,
            'status' => 200,
            'message' => [
                "Success display detail information of Souvenir Place"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->souvenirPlaceModel->get_sp_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find souvenir place by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGSP = $this->gallerySouvenirPlaceModel->delete_gallery($id);
        $deleteSP = $this->souvenirPlaceModel->delete(['id' => $id]);
        if ($deleteSP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete souvenir place"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }
}
