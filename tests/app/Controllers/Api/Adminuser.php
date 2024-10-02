<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Adminuser extends ResourceController
{
    use ResponseTrait;

    protected $servicePackageModel;
    protected $detailServicePackageModel;

    protected $db, $builder;

    public function __construct()
    {
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
        $response['title']='User List';
        // $users = new \Myth\Auth\Models\UserModel();
        // $data['users']=$users->findAll();

        $this->builder->select ('users.id as userid, username, email, name');
        $this->builder->join ('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join ('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $query = $this->builder->get();
        $response['users']=$query->getResult();
        
        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $response['title']='User List';

        $this->builder->select ('users.id as userid, username, email, fullname, user_image, name');
        $this->builder->join ('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join ('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where ('users.id', $id);
        $query = $this->builder->get();

        $response['user']=$query->getRow();

        if(empty($response['user'])){
            return redirect()->to('/admin');
        }

        return $this->respond($response);
    }

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
