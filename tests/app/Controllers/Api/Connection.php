<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Connection extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
    }

    public function index()
    {
        $db = db_connect();
        $content = [
            'Platform' => $db->getPlatform(),
            'Version' => $db->getVersion(),
            'Database' => $db->getDatabase(),
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Successfully Connected to Database"
            ]
        ];
        return $this->respond($response);
    }
}
