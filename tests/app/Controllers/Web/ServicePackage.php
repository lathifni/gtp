<?php

namespace App\Controllers\Web;

use App\Models\ServicePackageModel;
use App\Models\DetailServicePackageModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class ServicePackage extends ResourcePresenter
{
    protected $servicePackageModel;
    protected $detailServicePackageModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    public function show($id = null)
    {
        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_servicepackage', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'New Service Package',
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->servicePackageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'price' => $request['price'],
            'category' => $request['category']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addSP = $this->servicePackageModel->add_new_servicePackage($requestData);

        if ($addSP) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to('dashboard/service-package');
        }

        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'Edit Service Package',
            'data' => $sp,
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'price' => $request['price'],
            'category' => $request['category']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

        if ($updateSP) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createservicepackage($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'service_package_id' => $request['id_service'],
            'status' => $request['status_service'],
            'package_id' => $id,
        ];

        $checkExistingData = $this->detailServicePackageModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'This service already exists.');

            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addSP = $this->detailServicePackageModel->add_new_detail_service($id, $requestData);
       
            if ($addSP) {
                session()->setFlashdata('success', 'The service package has been successfully added.');

                return redirect()->back();
            } else {
                return redirect()->back()->withInput();
            }
        }
    }

    public function delete($id=null)
    {
        $request = $this->request->getPost();

        $package_id=$request['package_id'];
        $service_package_id=$request['service_package_id'];
        $name=$request['name'];
        $status=$request['status'];

        $array = array('package_id' => $package_id, 'service_package_id' => $service_package_id, 'status' => $status);
        $detailServicePackage = $this->detailServicePackageModel->where($array)->find();
        $deleteDSP= $this->detailServicePackageModel->where($array)->delete();

        if ($deleteDSP) {
            session()->setFlashdata('success', 'Service "'.$name.'" Successcully Deleted.');

            return redirect()->back();

            // return view('dashboard/detail-package-form', $data, $package, $packageDay, $detailPackage);

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
