<?php

namespace App\Controllers\Web;

use App\Models\FacilityModel;
use App\Models\GalleryFacilityModel;
use App\Models\FacilityTypeModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Facility extends ResourcePresenter
{
    protected $facilityModel;
    protected $galleryFacilityModel;
    protected $facilityTypeModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->facilityModel = new FacilityModel();
        $this->galleryFacilityModel = new GalleryFacilityModel();
        $this->facilityTypeModel = new FacilityTypeModel();
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
        $fc = $this->facilityModel->get_facility_by_id($id)->getRowArray();

        if (empty($fc)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryFacilityModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $fc['gallery'] = $galleries;

        $data = [
            'title' => $fc['name'],
            'data' => $fc,
            'folder' => 'facility'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_facility', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        $data = [
            'title' => 'New Facility',
            'facility' => $facility
        ];
        return view('dashboard/facility-form', $data);
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

        $id = $this->facilityModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_id' => $request['type'],
            'price' => $request['price'],
            'category' => $request['category']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addFC = $this->facilityModel->add_new_facility($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/facility');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryFacilityModel->add_new_gallery($id, $gallery);
        }

        if ($addFC) {
            return redirect()->to(base_url('dashboard/facility'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $fc = $this->facilityModel->get_facility_by_id($id)->getRowArray();
        if (empty($fc)) {
            return redirect()->to('dashboard/facility');
        }

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        $list_gallery = $this->galleryFacilityModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $fc['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Facility',
            'data' => $fc,
            'facility' => $facility
        ];
        return view('dashboard/facility-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_id' => $request['type'],
            'price' => $request['price'],
            'category' => $request['category']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updateFC = $this->facilityModel->update_facility($id, $requestData);
        $updateGeom = $this->facilityModel->update_geom($id, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/facility');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryFacilityModel->update_gallery($id, $gallery);
        } else {
            $this->galleryFacilityModel->delete_gallery($id);
        }

        if ($updateFC) {
            return redirect()->to(base_url('dashboard/facility') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
