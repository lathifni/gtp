<?php

namespace App\Controllers\Web;

use App\Models\SouvenirPlaceModel;
use App\Models\GallerySouvenirPlaceModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class SouvenirPlace extends ResourcePresenter
{
    protected $souvenirPlaceModel;
    protected $gallerySouvenirPlaceModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->gallerySouvenirPlaceModel = new GallerySouvenirPlaceModel();
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
        $souvenirplace = $this->souvenirPlaceModel->get_list_sp()->getResultArray();

        $data = [
            'title' => 'New Souvenir Place',
            'souvenirplace' => $souvenirplace
        ];
        return view('dashboard/souvenir-form', $data);
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
        $sp = $this->souvenirPlaceModel->get_sp_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->gallerySouvenirPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $sp['gallery'] = $galleries;

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
            'folder' => 'souvenir_place'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_souvenir_place', $data);
        }
        return view('web/detail_souvenir_place', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->souvenirPlaceModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'open' => $request['open'],
            'close' => $request['close'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addSP = $this->souvenirPlaceModel->add_new_sp($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/souvenir_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->gallerySouvenirPlaceModel->add_new_gallery($id, $gallery);
        }

        if ($addSP) {
            return redirect()->to(base_url('dashboard/souvenirplace'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $sp= $this->souvenirPlaceModel->get_sp_by_id($id)->getRowArray();
        if (empty($sp)) {
            return redirect()->to('dashboard/souvenirplace');
        }

        $list_gallery = $this->gallerySouvenirPlaceModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $sp['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Souvenir Place ',
            'data' => $sp
        ];
        return view('dashboard/souvenir-form', $data);
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

        $updatesp = $this->souvenirPlaceModel->update_sp($id, $requestData);
        $updateGeom = $this->souvenirPlaceModel->update_geom($id, $geom);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
    
                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/souvenir_place/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }
    
                $fileImg->move(FCPATH . 'media/photos/souvenir_place');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
    
            // Update or add gallery data
            if ($this->gallerySouvenirPlaceModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->gallerySouvenirPlaceModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->gallerySouvenirPlaceModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->gallerySouvenirPlaceModel->delete_gallery($id);
        }

        if ($updatesp) {
            return redirect()->to(base_url('dashboard/souvenirplace') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
