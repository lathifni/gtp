<?php

namespace App\Controllers\Web;

use App\Models\CulinaryPlaceModel;
use App\Models\GalleryCulinaryPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class CulinaryPlace extends ResourcePresenter
{
    protected $culinaryPlaceModel;
    protected $galleryCulinaryPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->galleryCulinaryPlaceModel = new GalleryCulinaryPlaceModel();
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
        $culinaryplace = $this->culinaryPlaceModel->get_list_cp()->getResultArray();

        $data = [
            'title' => 'New Culinary Place',
            'culinaryplace' => $culinaryplace
        ];
        return view('dashboard/culinary-form', $data);
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
        $cp = $this->culinaryPlaceModel->get_cp_by_id($id)->getRowArray();

        if (empty($cp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryCulinaryPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $cp['gallery'] = $galleries;

        $data = [
            'title' => $cp['name'],
            'data' => $cp,
            'folder' => 'culinary_place'
        ];
        if (url_is('*dashboard*')) {
            return view('dashboard/detail_culinary_place', $data);
        }
        return view('web/detail_culinary_place', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->culinaryPlaceModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'open' => $request['open'],
            'close' => $request['close'],
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

        $addFC = $this->culinaryPlaceModel->add_new_cp($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/culinary_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryCulinaryPlaceModel->add_new_gallery($id, $gallery);
        }

        if ($addFC) {
            return redirect()->to(base_url('dashboard/culinaryplace'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $cp = $this->culinaryPlaceModel->get_cp_by_id($id)->getRowArray();
        if (empty($cp)) {
            return redirect()->to('dashboard/culinaryplace');
        }

        $list_gallery = $this->galleryCulinaryPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $cp['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Culinary Place ',
            'data' => $cp
        ];

        // dd($data);
        return view('dashboard/culinary-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'open' => $request['open'],
            'close' => $request['close'],
            'capacity' => $request['capacity'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updateCP = $this->culinaryPlaceModel->update_cp($id, $requestData);
        $updateGeom = $this->culinaryPlaceModel->update_geom($id, $geom);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
    
                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/culinary_place/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }
    
                $fileImg->move(FCPATH . 'media/photos/culinary_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
    
            // Update or add gallery data
            if ($this->galleryCulinaryPlaceModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryCulinaryPlaceModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryCulinaryPlaceModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryCulinaryPlaceModel->delete_gallery($id);
        }

        if ($updateCP) {
            return redirect()->to(base_url('dashboard/culinaryplace') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
