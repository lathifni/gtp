<?php

namespace App\Controllers\Api;

use App\Models\PackageModel;
use App\Models\PackageDayModel;
use App\Models\PackageTypeModel;
use App\Models\GalleryPackageModel;
use App\Models\DetailServicePackageModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class PackageDay extends ResourceController
{
    use ResponseTrait;

    protected $packageModel;
    protected $packageDayModel;
    protected $packageTypeModel;
    protected $galleryPackageModel;
    protected $detailServicePackageModel;
    protected $db, $builder;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->packageTypeModel = new PackageTypeModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();

        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->packageModel->get_list_package()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Package"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $package = $this->packageModel->get_package_by_id($id)->getRowArray();

        $response = [
            'data' => $package,
            'status' => 200,
            'message' => [
                "Success display detail information of Package"
            ]
        ];
        return $this->respond($response);
    }

    public function getDay($id)
    {
        $packageDay = $this->packageDayModel->get_day_by_id($id)->getRow();

        $response = [
            'data' => $packageDay,
            'status' => 200,
            'message' => [
                "Success display day information of Package"
            ]
        ];
        return $this->respond($response);
    }

    public function findByName()
    {
        $request = $this->request->getPost();
        $name = $request['name'];
        $contents = $this->packageModel->get_package_by_name($name)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find package by name"
            ]
        ];
        return $this->respond($response);
    }

    public function type()
    {
        $contents = $this->packageTypeModel->get_list_type()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of package type"
            ]
        ];
        return $this->respond($response);
    }

    public function findByType()
    {
        $request = $this->request->getPost();
        $type = $request['type'];
        $contents = $this->packageModel->get_package_by_type($type)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find package by type"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGP = $this->galleryPackageModel->delete(['package_id' => $id]);
        $deleteS = $this->detailServicePackageModel->delete_detail_service(['package_id' => $id]);
        $deletePA = $this->packageModel->delete(['id' => $id]);
        if ($deletePA) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete package"
                ]
            ];
            return $this->respondDeleted($response);
            } else {
                $response = [
                    'status' => 404,
                    'message' => [
                        "Package not found"
                    ]
                ];
                return $this->failNotFound($response);
        }
    }
}
