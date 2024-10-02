<?php

namespace App\Controllers\Web;

use App\Models\VillageModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Village extends ResourcePresenter
{
    protected $villageModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;
    protected $db, $builder;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->villageModel = new VillageModel();
    }

//     public function digitasi()
//     {
//         $contents = $this->villageModel->get_all_data()->getResult();

//         $data = [
//             'title' => 'Digitasi Wilayah',
//             'digitasiwilayah'=>$contents,
//             'id'=> $id
//         ];
// dd($data);
//         return view('web/layouts/map-body-4', $data);
//         // dd($contents);

//     }

    public function index()
    {
        // $contents = $this->packageModel->get_list_package()->getResultArray();
        // $data = [
        //     'title' => 'Package',
        //     'data' => $contents,
        // ];

        // return view('web/list_package', $data);
    }

    public function show($id = null)
    {
        // $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        // if (empty($package)) {
        //     return redirect()->to(substr(current_url(), 0, -strlen($id)));
        // }

        // $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        // $galleries = array();
        // foreach ($list_gallery as $gallery) {
        //     $galleries[] = $gallery['url'];
        // }
        // $package['gallery'] = $galleries;
        
        // $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($id)->getResultArray();
        // $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($id)->getResultArray();

        // $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
        
        // $getday = $this->detailPackageModel->get_day_by_package($id)->getResultArray();

        // $combinedData = $this->detailPackageModel->getCombinedData();

        // $data = [
        //     'title' => $package['name'],
        //     'data' => $package,
        //     'serviceinclude' => $serviceinclude,
        //     'serviceexclude' => $serviceexclude,
        //     'day'=> $getday,
        //     'activity' => $combinedData,
        //     'folder' => 'package'
        // ];

        // if (url_is('*dashboard*')) {
        //     return view('dashboard/detail_package', $data);
        // }
        // return view('web/detail_package', $data);
    }

    public function new()
    {
        $id = $this->villageModel->get_new_id();

        $data = [
            'title' => 'New Village',
            'id'=> $id
        ];

        return view('dashboard/village-form', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->villageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['village'],
            'district' => $request['district'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addV = $this->villageModel->add_new_village($requestData, $geom);

        if ($addV) {
            return redirect()->to(base_url('dashboard/village/edit') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        } 
    }

    public function edit($id = null)
    {
        // $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        // if (empty($package)) {
        //     return redirect()->to('dashboard/package');
        // }

        // $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
        // $galleries = array();
        // foreach ($list_gallery as $gallery) {
        //     $galleries[] = $gallery['url'];
        // }
        // $package['gallery'] = $galleries;

        // $type = $this->packageTypeModel->get_list_type()->getResultArray();

        // $servicelist = $this->servicePackageModel->get_list_service_package()->getResultArray();
        // // $detailservice = $this->detailServicePackageModel->get_detailServicePackage_by_id($id)->getRowArray();

        // $this->builder->select ('service_package.id, service_package.name');
        // $this->builder->join ('detail_service_package', 'detail_service_package.package_id = package.id');
        // $this->builder->join ('service_package', 'service_package.id = detail_service_package.service_package_id', 'right');
        // $this->builder->where ('package.id', $id);
        // $query = $this->builder->get();
        // $datase['package']=$query->getResult();
        // $datases = $datase['package'];
        // $package['datase'] = $datases;

        // $servicepackage = $this->detailServicePackageModel->get_service_package_detail_by_id($id)->getResultArray();

        // $data = [
        //     'title' => 'Package',
        //     'id' => $id,
        //     'data' => $package,
        //     'type' => $type,
        //     'detailservice' => $servicepackage,
        //     'service' => $package['datase'],
        //     'servicelist' => $servicelist
        // ];

        // return view('dashboard/package-form', $data);
    }

    public function update($id = null)
    {
        // $request = $this->request->getPost();
        // $requestData = [
        //     'id' => $id,
        //     'name' => $request['name'],
        //     'type_id' => $request['type'],
        //     'price' => $request['price'],
        //     'description' => $request['description'],
        //     'contact_person' => $request['contact_person']
        // ];
        // foreach ($requestData as $key => $value) {
        //     if (empty($value)) {
        //         unset($requestData[$key]);
        //     }
        // }

        // $geom = $request['multipolygon'];
        // // $geojson = $request['geo-json'];

        // if (isset($request['video'])) {
        //     $folder = $request['video'];
        //     $filepath = WRITEPATH . 'uploads/' . $folder;
        //     $filenames = get_filenames($filepath);
        //     $vidFile = new File($filepath . '/' . $filenames[0]);
        //     $vidFile->move(FCPATH . 'media/videos');
        //     delete_files($filepath);
        //     rmdir($filepath);
        //     $requestData['video_url'] = $vidFile->getFilename();
        // } else {
        //     $requestData['video_url'] = null;
        // }
        // $updatePA = $this->packageModel->update_package($id, $requestData);
        // $updateGeom = $this->packageModel->update_geom($id, $geom);

        // if (isset($request['gallery'])) {
        //     $folders = $request['gallery'];
        //     $gallery = array();
        //     foreach ($folders as $folder) {
        //         $filepath = WRITEPATH . 'uploads/' . $folder;
        //         $filenames = get_filenames($filepath);
        //         $fileImg = new File($filepath . '/' . $filenames[0]);
        //         $fileImg->move(FCPATH . 'media/photos/package');
        //         delete_files($filepath);
        //         rmdir($filepath);
        //         $gallery[] = $fileImg->getFilename();
        //     }
        //     $this->galleryPackageModel->update_gallery($id, $gallery);
        // } else {
        //     $this->galleryPackageModel->delete_gallery($id);
        // }

        // if ($updatePA) {
        //     return redirect()->to(base_url('dashboard/package') . '/' . $id);
        // } else {
        //     return redirect()->back()->withInput();
        // }
    }
}
