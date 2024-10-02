<?php

namespace App\Controllers\Web;

use App\Models\HomestayModel;
use App\Models\UnitHomestayModel;
use App\Models\HomestayUnitTypeModel;
use App\Models\FacilityUnitModel;
use App\Models\FacilityUnitDetailModel;
use App\Models\GalleryUnitModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class UnitHomestay extends ResourcePresenter
{
    protected $homestayModel;
    protected $unitHomestayModel;
    protected $homestayUnitTypeModel;
    protected $facilityUnitModel;
    protected $facilityUnitDetailModel;
    protected $galleryUnitModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->homestayModel = new HomestayModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->homestayUnitTypeModel = new HomestayUnitTypeModel();
        $this->facilityUnitModel = new FacilityUnitModel();
        $this->facilityUnitDetailModel = new FacilityUnitDetailModel();
        $this->galleryUnitModel = new GalleryUnitModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    // public function show($id = null)
    // {
    //     $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

    //     if (empty($sp)) {
    //         return redirect()->to(substr(current_url(), 0, -strlen($id)));
    //     }

    //     $data = [
    //         'title' => $sp['name'],
    //         'data' => $sp,
    //     ];

    //     if (url_is('*dashboard*')) {
    //         return view('dashboard/detail_servicepackage', $data);
    //     }
    // }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new($id = null)
    {
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        $facilityUnit = $this->facilityUnitModel->get_list_facility_unit()->getResultArray();

        $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getResultArray();

        $facilities = array();
        foreach ($list_unit as $unithome) {
            $homestay_id=$unithome['homestay_id'];
            $unit_number=$unithome['unit_number'];
            $unit_type=$unithome['unit_type'];

            $list_facility = $this->facilityUnitDetailModel->get_facility_unit_detail($homestay_id, $unit_type, $unit_number)->getResultArray();
            $facilities[]=$list_facility;
        }
            
        $fc = $facilities;

        $unittype = $this->homestayUnitTypeModel->get_list_type()->getResultArray();

        $list_gallery_unit = $this->galleryUnitModel->get_gallery($id)->getResultArray();

        $data = [
            'title' => 'Unit Homestay',
            'homestay_id'=>$id,
            'data'=>$homestay,
            'unit' => $list_unit,
            'unit_type' => $unittype,
            'gallery_unit' => $list_gallery_unit,
            'facility_unit' => $facilityUnit,
            'facility'=> $fc,
        ];
        return view('dashboard/unit-homestay-form', $data);
    }


    public function createunit($id)
    {
        $request = $this->request->getPost();

        $type = $request['unit_type'];
        $unit_number = $this->unitHomestayModel->get_new_unit_number($id, $type);

        $requestData = [
            'unit_number' => $unit_number,
            'homestay_id' => $id,
            'unit_type' => $request['unit_type'],
            'nama_unit' => $request['nama_unit'],
            'capacity' => $request['capacity'],
            'price' => $request['price'],
            'description' => $request['description']
        ];

        $unit_number=$requestData['unit_number'];
        $homestay_id=$requestData['homestay_id'];
        $unit_type=$requestData['unit_type'];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $addUH = $this->unitHomestayModel->add_new_unitHomestay($requestData);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);

                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/unithomestay/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }

                $fileImg->move(FCPATH . 'media/photos/unithomestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }

            // Update or add gallery data
            if ($this->galleryUnitModel->isGalleryExist($unit_number, $homestay_id, $unit_type,)) {
                // Update gallery with the new or existing file names
                $this->galleryUnitModel->update_gallery($unit_number, $homestay_id, $unit_type, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryUnitModel->add_new_gallery($unit_number, $homestay_id, $unit_type, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryUnitModel->delete_gallery($unit_number, $homestay_id, $unit_type,);
        }

                
        if ($addUH) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createfacilityunit($id)
    {
        $request = $this->request->getPost();

        $unitdata  = $_POST['unit_homestay'];
        $input_arr = explode("-", $unitdata);
        $input_arr[0]; 
        $input_arr[1]; 
        $input_arr[2]; 

        $requestData = [
            'facility_unit_id' => $request['facility_unit_id'],
            'homestay_id' => $input_arr[0],
            'unit_type' => $input_arr[1],
            'unit_number' => $input_arr[2],
            'description' => $request['description_facility']
        ];

        $addFU = $this->facilityUnitDetailModel->add_new_facilityUnitDetail($id, $requestData);

        if ($addFU) {
            return redirect()->back();
        } else {
            $session = session();
            $session->setFlashdata('error', 'Data tersebut sudah ada');
            return redirect()->back();        
        }
    }

    public function deletefacilityunit ($homestay_id=null, $unit_type=null, $unit_number=null, $facility_unit_id=null, $description=null)
    {
        $request = $this->request->getPost();

        $homestay_id=$request['homestay_id'];
        $unit_type=$request['unit_type'];
        $unit_number=$request['unit_number'];
        $facility_unit_id=$request['facility_unit_id'];
        $description=$request['description'];

        $data_unit = $this->unitHomestayModel->get_unit_homestay_selected($homestay_id, $unit_type, $unit_number)->getRowArray();
        $data_facility = $this->facilityUnitModel->get_facility_unit_selected($facility_unit_id)->getRowArray();

        $array = array('homestay_id' => $homestay_id, 'unit_type' => $unit_type,'unit_number' => $unit_number, 'facility_unit_id' => $facility_unit_id, 'description' => $description);
        $facilityUnitDetail= $this->facilityUnitDetailModel->where($array)->find();
        $deleteFUD= $this->facilityUnitDetailModel->where($array)->delete();

        if ($deleteFUD) {
            session()->setFlashdata('pesan', 'Facility Unit Deleted Successfully.');
            
            return redirect()->back();

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

    public function createfacility($id)
    {
        $request = $this->request->getPost();

        $id_facility = $this->facilityUnitModel->get_new_id();

        $requestData = [
            'id' => $id_facility,
            'name' => $request['facility_name'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addFU = $this->facilityUnitModel->add_new_facilityUnit($requestData);

        if ($addFU) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {        
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getResultArray();
        $uh = $this->unitHomestayModel->get_unit_homestay_by_id($id)->getRowArray();
        $unitHomestay = $this->unitHomestayModel->get_list_unit_homestay()->getResultArray();
        $unittype = $this->homestayUnitTypeModel->get_list_type()->getResultArray();
        $facilityUnit = $this->facilityUnitModel->get_list_facility_unit()->getResultArray();

        $facilities = array();
        $galleries = array();

        foreach ($list_unit as $unithome) {
            $homestay_id=$unithome['homestay_id'];
            $unit_number=$unithome['unit_number'];
            $unit_type=$unithome['unit_type'];

            $list_facility = $this->facilityUnitDetailModel->get_facility_unit_detail($homestay_id, $unit_type, $unit_number)->getResultArray();

            $facilities[]=$list_facility;
            $fc = $facilities;

            $list_gallery = $this->galleryUnitModel->get_gallery($homestay_id, $unit_type, $unit_number)->getResultArray();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }

        }
        $homestay['gallery'] = $galleries;

        if(empty($fc)){
            $data = [
                'title' => 'Unit Homestay',
                'homestay_id' => $id,
                'data' => $homestay,
                'unit_type' => $unittype,
                'unit' => $list_unit,
                'uh' => $uh,
                'unithomestay' => $unitHomestay,
                'facility_unit' => $facilityUnit,
            ];      
        } else {
            $data = [
                'title' => 'Unit Homestay',
                'homestay_id' => $id,
                'data' => $homestay,
                'unit_type' => $unittype,
                'unit' => $list_unit,
                'uh' => $uh,
                'unithomestay' => $unitHomestay,
                'facility_unit' => $facilityUnit,
                'facility'=> $fc,
            ];
        }  
        //  dd($data);

        return view('dashboard/unit-homestay-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'nama_unit' => $request['editNama_unit'],
            'capacity' => $request['editCapacity'],
            'price' => $request['editPrice'],
            'description' => $request['editDescription'],
        ];
        // dd($requestData);

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $unit_number=$request['editnumber'];
        $homestay_id=$request['editHomestay'];
        $unit_type=$request['editunit_type'];

        $updateUH = $this->unitHomestayModel->update_unit_homestay($unit_number, $homestay_id, $unit_type, $requestData);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);

                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/unithomestay/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }

                $fileImg->move(FCPATH . 'media/photos/unithomestay');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }

            // Update or add gallery data
            if ($this->galleryUnitModel->isGalleryExist($unit_number, $homestay_id, $unit_type,)) {
                // Update gallery with the new or existing file names
                $this->galleryUnitModel->update_gallery($unit_number, $homestay_id, $unit_type, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryUnitModel->add_new_gallery($unit_number, $homestay_id, $unit_type, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryUnitModel->delete_gallery($unit_number, $homestay_id, $unit_type,);
        }


        if ($updateUH) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }



    public function show($id = null)
    {
        $homestay = $this->homestayModel->get_homestay_by_id($id)->getRowArray();

        $list_unit = $this->unitHomestayModel->get_unit_homestay($id)->getResultArray();

        $unithomes = array();
        foreach ($list_unit as $unithome) {
            $unithomes[] = $unithome['id'];
        }
        $homestay['unithomes'] = $unithomes;

        $facilities = array();
        foreach ($homestay['unithomes'] as $uh_id) {
            $unit_homestay_id=$uh_id;
            $list_facility = $this->facilityUnitDetailModel->get_facility_unit_detail($unit_homestay_id)->getResultArray();
            $facilities[]=$list_facility;
        }
        $fc = $facilities;

        $data = [
            'title' => $homestay['name'],
            'data' => $homestay,
            'unit' => $list_unit,
            'facility' => $fc,
            'folder' => 'homestay'
        ];


        if (url_is('*dashboard*')) {
            return view('dashboard/detail_homestay', $data);
        }
        return view('web/detail_homestay',$data);
    }

    public function delete ($homestay_id=null, $unit_type=null, $unit_number=null, $unit_homestay_id=null)
    {
        $request = $this->request->getPost();
        
        $homestay_id=$request['homestay_id'];
        $unit_type=$request['unit_type'];
        $unit_number=$request['unit_number'];
        $nama_unit=$request['nama_unit'];
        $description=$request['description'];

        $array1 = array('homestay_id' => $homestay_id, 'unit_type' => $unit_type,'unit_number' => $unit_number);
        $facilityUnitDetail = $this->facilityUnitDetailModel->where($array1)->find();
        $deleteFUD= $this->facilityUnitDetailModel->where($array1)->delete();
        $gUnitDetail = $this->galleryUnitModel->where($array1)->find();
        $deleteGUD= $this->galleryUnitModel->where($array1)->delete();

        if ($deleteGUD){
            $array2 = array('homestay_id' => $homestay_id, 'unit_type' => $unit_type,'unit_number' => $unit_number, 'nama_unit'=> $nama_unit,'description'=> $description);
            $unitHomestay = $this->unitHomestayModel->where($array2)->find();
            $deleteUH= $this->unitHomestayModel->delete_unit($array2);

            if ($deleteUH) {
                session()->setFlashdata('pesan', 'Unit "'.$nama_unit.' '.$unit_number.'" Homestay "'.$homestay_id.'" Deleted Successfully.');
                
                return redirect()->back();

            } else {
                $response = [
                    'status' => 404,
                    'message' => [
                        "Unit Homestay not found"
                    ]
                ];
                return $this->failNotFound($response);
            }
        }
    }
}
