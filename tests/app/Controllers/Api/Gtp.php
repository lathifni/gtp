<?php

namespace App\Controllers\Api;

use App\Models\GtpModel;
use App\Models\GalleryGtpModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Gtp extends ResourceController
{
    use ResponseTrait;

    protected $gtpModel;
    protected $galleryGtpModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->galleryGtpModel = new GalleryGtpModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->gtpModel->get_gtp()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryGtpModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];

        return $this->respond($response);
    }

    public function show($id = null)
    {
        $gtp = $this->gtpModel->get_gtp_marker($id)->getRowArray();

        $response = [
            'data' => $gtp,
            'status' => 200,
            'message' => [
                "Success display detail information of GTP"
            ]
        ];
        return $this->respond($response);
    }
}
