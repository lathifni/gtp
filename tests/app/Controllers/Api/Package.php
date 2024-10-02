<?php

namespace App\Controllers\Api;

use App\Models\PackageModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
use App\Models\GalleryPackageModel;
use App\Models\PackageTypeModel;
use App\Models\ServicePackageModel;
use App\Models\DetailServicePackageModel;
use App\Models\ReservationModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Package extends ResourceController
{
    use ResponseTrait;

    protected $packageModel;
    protected $detailPackageModel;
    protected $packageDayModel;
    protected $galleryPackageModel;
    protected $packageTypeModel;
    protected $servicePackageModel;
    protected $detailServicePackageModel;
    protected $reservationModel;
    protected $db, $builder;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->packageTypeModel = new PackageTypeModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->reservationModel = new ReservationModel();

        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->builder = $this->db->table('package');
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


    public function detail($id = null)
    {
        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        if (empty($package)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $package['gallery'] = $galleries;
        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($id)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($id)->getResultArray();

        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
        
        $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();

        $combinedData = $this->detailPackageModel->getCombinedData($id);
        // $routeData = $this->detailPackageModel->getRouteData();

        $review = $this->reservationModel->getReview($id)->getResultArray();
        $rating = $this->reservationModel->getRating($id)->getRowArray();

        $data = [
            'title' => $package['name'],
            'data' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'activity' => $combinedData,
            // 'route' => $routeData,
            'review'=>$review,
            'rating'=>$rating,
            'folder' => 'package'
        ];


        if (url_is('*dashboard*')) {
            return view('dashboard/detail_package', $data);
        }
        return view('maps/detail_package', $data);
    }
}
