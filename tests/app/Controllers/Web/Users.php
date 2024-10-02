<?php

namespace App\Controllers\Web;

use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\GroupModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use Myth\Auth\Passwords\Password;

class Users extends ResourcePresenter
{
    protected $userModel;
    protected $groupModel;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->auth   = service('authentication');

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
        $us = $this->userModel->get_users_by_id($id)->getRowArray();

        if (empty($us)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $us['username'],
            'data' => $us,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_users', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {

        $data = [
            'title' => 'New User',
        ];
        return view('dashboard/admin-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function adminregister()
    {
        $request = $this->request->getPost();

        $id = $this->userModel->get_new_id();
        
        $password = '1234567';
        
        $requestData = [
            'id' => $id,
            'username' => $request['username'],
            'email' => $request['email'],
            'password_hash' => '$2y$10$gnvUE.vqFBKC4xwi.xBJTuURWh6ydKh9jUVR4R3T3XojTOST.oIaO',
            'user_image' => 'default.png',
            'active' => '1'
        ];

        $groupId='1';
        

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addUS = $this->userModel->add_new_user($requestData);
        $addUG = $this->groupModel->addUserToGroup($id, $groupId);

        if ($addUS && $addUG) {
            session()->setFlashdata('success', 'Admin account "'.$requestData['username'].'" created');

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

    // public function createservicepackage($id)
    // {
    //     $request = $this->request->getPost();

    //     $requestData = [
    //         'service_package_id' => $request['id_service'],
    //         'status' => $request['status_service'],
    //         'package_id' => $id,
    //     ];

    //     $checkExistingData = $this->detailServicePackageModel->checkIfDataExists($requestData);

    //     if ($checkExistingData) {
    //         // Data sudah ada, set pesan error flash data
    //         session()->setFlashdata('failed', 'Service is available.');

    //         return redirect()->back()->withInput();
    //     } else {
    //         // Data belum ada, jalankan query insert
    //         $addSP = $this->detailServicePackageModel->add_new_detail_service($id, $requestData);
       
    //         if ($addSP) {
    //             session()->setFlashdata('success', 'Service package added successfully.');

    //             return redirect()->back();
    //         } else {
    //             return redirect()->back()->withInput();
    //         }
    //     }
    // }

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
            session()->setFlashdata('success', 'Service "'.$name.'" deleted successfully.');

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
