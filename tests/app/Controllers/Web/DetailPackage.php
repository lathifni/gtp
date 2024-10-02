<?php

namespace App\Controllers\Web;

use App\Models\DetailPackageModel;
use App\Models\AttractionModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class DetailPackage extends ResourcePresenter
{
    protected $detailPackageModel;
    protected $attractionModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->detailPackageModel = new DetailPackageModel();
        $this->attractionModel = new AttractionModel();
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
        $dp = $this->detailPackageModel->get_detailPackage_by_id($id)->getRowArray();

        if (empty($dp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $dp['name'],
            'data' => $dp,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_detailpackage', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new($id)
    {
        $packageDay = $this->packageDayModel->get_list_package_day($id)->getResultArray();

        $data = [
            'title' => 'New Detail Package',
            'packageday' => $packageDay
        ];
        return view('dashboard/detail-package-form', $data);
    }

    // get object by code type 
    public function get_object(){
        $data = $this->attractionModel->get_object()->result();
        echo json_encode($data);
    }


    function ambil_data(){

        $modul=$this->input->post('activity_type');
        $id=$this->input->post('id');
        
        if($modul=="A"){
            echo $this->attractionModel->get_attraction();
        }
        else if($modul=="kecamatan"){
        
        }
        else if($modul=="kelurahan"){
        
        }
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

        $id = $this->detailPackageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addDP = $this->detailPackageModel->add_new_detailPackage($requestData);

        if ($addDP) {
            return redirect()->to(base_url('dashboard/detailpackage'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $dp = $this->detailPackageModel->get_detailPackage_by_id($id)->getRowArray();
        if (empty($dp)) {
            return redirect()->to('dashboard/detail-package');
        }

        $detailPackage = $this->detailPackageModel->get_list_detail_package()->getResultArray();

        $data = [
            'title' => 'Edit Detail Package',
            'data' => $dp,
            'facility' => $detailPackage
        ];
        return view('dashboard/detail-package-form', $data);
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

        $updateDP = $this->detailPackageModel->update_detailPackage($id, $requestData);

        if ($updateDP) {
            return redirect()->to(base_url('dashboard/detailpackage') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    // public function delete($package_id,$day,$activity)
    // {
    //     dd($package_id,$day,$activity);
    //     // cari gambar berdasarkan id
    //     // $komik = $this->komikModel->find($id); 

    //     // // cek jika file gambarnya default.jpg
    //     // if ($komik['sampul'] != 'default.png') {
    //     //     // hapus gambar
    //     //     unlink('img/' . $komik['sampul']);
    //     // }

    //     $this->detailPackageModel->delete($package_id,$day,$activity);
    //     session()->setFlashdata('pesan', 'Data Berhasil di Hapus.');
    //     return redirect()->to('/packageday/P0014');
    // }
}
