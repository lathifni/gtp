<?php

namespace App\Controllers\Web;

use App\Models\GtpModel;
use App\Models\AttractionModel;
use App\Models\GalleryAttractionModel;
use App\Models\FacilityTypeModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Unik extends ResourcePresenter
{
    protected $gtpModel;
    protected $attractionModel;
    protected $galleryAttractionModel;
    protected $facilityTypeModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->attractionModel = new AttractionModel();
        $this->galleryAttractionModel = new GalleryAttractionModel();
        $this->facilityTypeModel = new FacilityTypeModel();
    }

    public function index()
    {
        $contents = $this->attractionModel->get_tracking()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Tracking Mangrove',
            'folder' => 'attraction',
            'data' => $contents,
            'facility' => $facility
        ];

        return view('web/tracking_mangrove', $data);
    }

    public function estuaria()
    {
        $contents = $this->attractionModel->get_estuaria()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Estuaria',
            'folder' => 'attraction',
            'data' => $contents,
            'facility' => $facility
        ];

        return view('web/estuaria', $data);
    }

    public function pieh()
    {
        $contents = $this->attractionModel->get_pieh()->getResultArray();
        $contents2 = $this->gtpModel->get_gtp()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Trip Pieh Island',
            'folder' => 'attraction',
            'data' => $contents,
            'data2' => $contents2,
            'facility' => $facility
        ];

        return view('web/pieh', $data);
    }

    public function makam()
    {
        $contents = $this->attractionModel->get_makam()->getResultArray();
        $contents2 = $this->gtpModel->get_gtp()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Makam Syekh Burhanuddin',
            'folder' => 'attraction',
            'data' => $contents,
            'data2' => $contents2,
            'facility' => $facility
        ];

        return view('web/makam', $data);
    }
}
