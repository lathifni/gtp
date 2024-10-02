<?php

namespace App\Controllers\Api;

use App\Models\DetailPackageModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Detailpackage extends ResourceController
{
    use ResponseTrait;

    protected $detailPackageModel;

    public function __construct()
    {
        $this->detailPackageModel = new DetailPackageModel();

    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    // public function index()
    // {
    //     $contents = $this->servicePackageModel->get_list_service_package()->getResult();
    //     $response = [
    //         'data' => $contents,
    //         'status' => 200,
    //         'message' => [
    //             "Success get list of Service Package"
    //         ]
    //     ];
    //     return $this->respond($response);
    // }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    // public function show($id = null)
    // {
    //     $servicePackage = $this->servicePackageModel->get_facility_by_id($id)->getRowArray();

    //     $response = [
    //         'data' => $servicePackage,
    //         'status' => 200,
    //         'message' => [
    //             "Success display detail information of Service Package"
    //         ]
    //     ];
    //     return $this->respond($response);
    // }

    // public function delete($id)
    // {

    //     $deleteDP = $this->detailPackageModel->delete(['package_id' => $id]);

    //     if ($deleteDP) {
    //         $response = [
    //             'status' => 200,
    //             'message' => [
    //                 "Success delete Activity Package Day"
    //             ]
    //         ];
    //         return $this->respondDeleted($response);
    //     }
    // }

    // public function new($id = null)
    // {
    //     $servicePackage = $this->servicePackageModel->get_package_by_id($id)->getRowArray();

    //     $response = [
    //         'data' => $servicePackage,
    //         'status' => 200,
    //         'message' => [
    //             "Success display detail information of Service Package"
    //         ]
    //     ];
    //     return $this->respond($response);
    // }
}
