<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\GalleryHomestayModel;
use App\Models\GalleryUnitModel;
use App\Models\UnitHomestayModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use App\Models\FacilityHomestayModel;
use App\Models\FacilityHomestayDetailModel;
use App\Models\DetailReservationModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Homestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $galleryHomestayModel;
    protected $galleryUnitModel;
    protected $unitHomestayModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;
    protected $facilityHomestayModel;
    protected $facilityHomestayDetailModel;
    protected $detailReservationModel;
    
    protected $helpers = ['auth', 'url', 'filesystem'];

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
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResultArray();
        $data = [
            'title' => 'Homestay',
            'data' => $contents,
        ];

        return view('web/list_homestay', $data);
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
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

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data);
        }
        return view('web/detail_homestay',$data);
    }

    public function new()
    {
        $facility = $this->facilityHomestayModel->get_list_facility_homestay()->getResultArray();
        $id = $this->homestayModel->get_new_id();

        $data = [
            'title' => 'New Homestay',
            'homestay_id'=>$id,
            'facility' => $facility,
        ];
        
        return view('dashboard/homestay-form', $data);
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

        $id = $this->homestayModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'description' => $request['description']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addHM = $this->homestayModel->add_new_homestay($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/homestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryHomestayModel->add_new_gallery($id, $gallery);
        }

        if ($addHM) {
            return redirect()->to(base_url('dashboard/homestay/').$id.'/edit');
        } else {
            $session = session();
            $session->setFlashdata('error', 'Data tersebut sudah ada');
            return redirect()->back()->withInput();
        }
    }

    public function createafacilityhomestay($id)
    {
        $request = $this->request->getPost();
        // $id = $this->homestayModel->get_new_id();

        $requestData = [
            'facility_homestay_id' => $request['facility_homestay_id'],
            'homestay_id' => $id,
            'description' => $request['description_facility']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $checkExistingData = $this->facilityHomestayDetailModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            $session = session();
            $session->setFlashdata('error', 'The data already exists');
            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addFH = $this->facilityHomestayDetailModel->add_new_facilityHomestayDetail($requestData);
       
            if ($addFH) {
                return redirect()->to(base_url('dashboard/homestay/new/').$id);
            } else {
                return redirect()->back()->withInput();
            }
        }
        
    }


    public function edit($id = null)
    {
        $facility = $this->facilityHomestayModel->get_list_facility_homestay()->getResultArray();

        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();
        if (empty($homestay)) {
            return redirect()->to('dashboard/homestay');
        }

        $list_gallery = $this->galleryHomestayModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $homestay['gallery'] = $galleries;

        $facilityHomestay= $this->facilityHomestayDetailModel->get_detailFacilityHomestay_by_id($id)->getResultArray();

        $data = [
            'title' => 'Homestay',
            'data' => $homestay,
            'facility' => $facility,
            'facility_homestay'=>$facilityHomestay

        ];

        return view('dashboard/homestay-form', $data);
    }

    public function update($id = null)
    {

        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updateHM = $this->homestayModel->update_homestay($id, $requestData);
        $updateGeom = $this->homestayModel->update_geom($id, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/homestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryHomestayModel->add_new_gallery($id, $gallery);
        }

        if ($updateHM) {
            return redirect()->to(base_url('dashboard/homestay') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    
    // public function get_list_hm_api() {
    //     $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
    //     $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.address,{$this->table}.contact_person,{$this->table}.description";
    //     $vilGeom = "village.id = '1' AND ST_Contains(village.geom, {$this->table}.geom)";
    //     $query = $this->db->table($this->table)
    //         ->select("{$columns}, {$coords}")
    //         ->from('village')
    //         ->where($vilGeom)
    //         ->get();
    //     return $query;
    // }
}
