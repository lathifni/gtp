<?php

namespace App\Controllers\Api;

use Myth\Auth\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();

    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->userModel->get_admin()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of users"
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
        $user = $this->userModel->get_users_by_id($id)->getRowArray();

        $response = [
            'data' => $user,
            'status' => 200,
            'message' => [
                "Success display detail information of users"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteUS = $this->userModel->delete(['id' => $id]);
        // dd($deleteUS);
        if ($deleteUS) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete users"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function new($id = null)
    {
        $user = $this->userModel->get_users_by_id($id)->getRowArray();

        $response = [
            'data' => $user,
            'status' => 200,
            'message' => [
                "Success display detail information of users"
            ]
        ];
        return $this->respond($response);
    }

    
}
