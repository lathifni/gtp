<?php

namespace App\Controllers\Api;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use App\Models\GalleryUnitModel;
use App\Models\UnitHomestayModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use App\Models\FacilityHomestayModel;
use App\Models\FacilityHomestayDetailModel;
use App\Models\DetailReservationModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Homestay extends ResourceController
{
    use ResponseTrait;

    protected $homestayModel;
    protected $galleryHomestayModel;
    protected $galleryUnitModel;
    protected $unitHomestayModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;
    protected $facilityHomestayModel;
    protected $facilityHomestayDetailModel;
    protected $detailReservationModel;

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->galleryHomestayModel = new GalleryHomestayModel();
        $this->galleryUnitModel = new GalleryUnitModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->facilityUnitModel = new FacilityUnitModel();
        $this->facilityUnitDetailModel = new FacilityUnitDetailModel();
        $this->facilityHomestayModel = new FacilityHomestayModel();
        $this->facilityHomestayDetailModel = new FacilityHomestayDetailModel();
        $this->detailReservationModel = new DetailReservationModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Homestay"
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
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }

        $homestay['gallery'] = $galleries;

        $response = [
            'data' => $homestay,
            'status' => 200,
            'message' => [
                "Success display detail information of Homestay"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->homestayModel->get_homestay_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find homestay by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGHM = $this->galleryHomestayModel->delete(['homestay_id' => $id]);
        $deleteHM = $this->homestayModel->delete(['id' => $id]);
        if ($deleteHM) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete homestay"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function maps() {
        $contents = $this->homestayModel->get_list_hm_api()->getResultArray();
        $data = [
            'title' => 'Homestay',
            'data' => $contents,
        ];
        // dd($data);
        return view('maps/homestay', $data);
    }

    public function detail($id) {
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        if (empty($homestay)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_facility_home = $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($id)->getResultArray();

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $homestay['gallery'] = $galleries;

        $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getResultArray();

        $facilities = array();
        foreach ($list_unit as $unit) {
            $unit_number=$unit['unit_number'];
            $homestay_id=$unit['homestay_id'];
            $unit_type=$unit['unit_type'];
            $list_facility = $this->facilityUnitDetailModel->get_data_facility_unit_detail($unit_number, $homestay_id, $unit_type)->getResultArray();
            $facilities[]=$list_facility;

        }
        $fc = $facilities;

        $list_gallery_unit = $this->galleryUnitModel->get_gallery($id)->getResultArray();

        $datareview = array();
        $datarating = array();
        foreach($list_unit as $unit){
            $unit_number=$unit['unit_number'];
            $homestay_id=$unit['homestay_id'];
            $unit_type=$unit['unit_type'];
            $dreview = $this->detailReservationModel->getReview($unit_number, $homestay_id, $unit_type)->getResultArray();
            $drating = $this->detailReservationModel->getRating($unit_number, $homestay_id, $unit_type)->getResultArray();
            $datareview[]=$dreview;
            $datarating[]=$drating;

        }

        $review = $datareview;
        $rating = $datarating;

        $data = [
            'title' => $homestay['name'],
            'data' => $homestay,
            'facilityhome' => $list_facility_home,
            'unit' => $list_unit,
            'gallery_unit' => $list_gallery_unit,
            'facility' => $fc,
            'review' => $review,
            'rating' => $rating,
            'folder' => 'homestay'
        ];

        return view('maps/detail_homestay',$data);
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<20; $h++){
            if ($h < 10) {
                $valueHome= 'HO00'.$h;
            } elseif ($h > 9) {
                $valueHome= 'HO0'.$h;
            }

            if ($digitasi == $valueHome) {
                $digiProperty = $this->homestayModel->get_object($valueHome)->getRowArray();
                $geoJson = json_decode($this->homestayModel->get_geoJson($valueHome)->getRowArray()['geoJson']);
            } 
        }
        
        $content = [
            'type' => 'Feature',
            'geometry' => $geoJson,
            'properties' => [
                'id' => $digiProperty['id'],
                'name' => $digiProperty['name'],
                'lat' => $digiProperty['lat'],
                'lng' => $digiProperty['lng'],
            ]
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];
        return $this->respond($response);
    }
}
