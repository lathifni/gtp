<?php

namespace App\Controllers\Web;

use App\Models\WorshipPlaceModel;
use App\Models\GalleryWorshipPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class WorshipPlace extends ResourcePresenter
{
    protected $worshipPlaceModel;
    protected $galleryWorshipPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->galleryWorshipPlaceModel = new GalleryWorshipPlaceModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    public function new()
    {
        $worshipplace = $this->worshipPlaceModel->get_list_wp()->getResultArray();

        $data = [
            'title' => 'New Worship Place',
            'worshipplace' => $worshipplace
        ];
        return view('dashboard/worship-form', $data);
    }


    /**
     * Present a view to present a wpecific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $wp = $this->worshipPlaceModel->get_wp_by_id($id)->getRowArray();

        if (empty($wp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryWorshipPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $wp['gallery'] = $galleries;

        $data = [
            'title' => $wp['name'],
            'data' => $wp,
            'folder' => 'worship_place'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_worship_place', $data);
        }
        return view('web/detail_worship_place', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->worshipPlaceModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'capacity' => $request['capacity'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addWP = $this->worshipPlaceModel->add_new_wp($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/worship_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryWorshipPlaceModel->add_new_gallery($id, $gallery);
        }

        if ($addWP) {
            return redirect()->to(base_url('dashboard/worshipplace'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $wp = $this->worshipPlaceModel->get_wp_by_id($id)->getRowArray();
        if (empty($wp)) {
            return redirect()->to('dashboard/worshipplace');
        }

        $list_gallery = $this->galleryWorshipPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $wp['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Worship Place ',
            'data' => $wp
        ];
        return view('dashboard/worship-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'capacity' => $request['capacity'],
            'description' => $request['description'],
            // 'status' => $request['status']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updatewp = $this->worshipPlaceModel->update_wp($id, $requestData);
        $updateGeom = $this->worshipPlaceModel->update_geom($id, $geom);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
    
                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/worship_place/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }
    
                $fileImg->move(FCPATH . 'media/photos/worship_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
    
            // Update or add gallery data
            if ($this->galleryWorshipPlaceModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryWorshipPlaceModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryWorshipPlaceModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryWorshipPlaceModel->delete_gallery($id);
        }


        if ($updatewp) {
            return redirect()->to(base_url('dashboard/worshipplace') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
