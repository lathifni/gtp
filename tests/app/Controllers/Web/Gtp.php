<?php

namespace App\Controllers\Web;

use App\Models\GtpModel;
use App\Models\ProvinsiModel;
use App\Models\GalleryGtpModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Gtp extends ResourcePresenter
{
    protected $gtpModel;
    protected $provinsiModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->provinsiModel = new ProvinsiModel();
        $this->galleryGtpModel = new GalleryGtpModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->gtpModel->get_gtp()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryGtpModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Home',
            'data' => $contents
        ];

        return view('web/info_home', $data);
    }

    public function edit($id = null)
    {
        $contents = $this->gtpModel->get_gtp()->getRowArray();
        if (empty($contents)) {
            return redirect()->to('dashboard/gtp');
        }

        $list_gallery = $this->galleryGtpModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $contents['gallery'] = $galleries;

        $data = [
            'title' => 'Edit GTP Information',
            'data' => $contents,
        ];
        return view('dashboard/gtp-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_of_tourism' => $request['type_of_tourism'],
            'address' => $request['address'],
            'open' => $request['open'],
            'close' => $request['close'],
            'ticket_price' => $request['ticket_price'],
            'contact_person' => $request['contact_person']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateGTP = $this->gtpModel->update_gtp($id, $requestData);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/gtp');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryGtpModel->update_gallery($id, $gallery);
        } else {
            $this->galleryGtpModel->delete_gallery($id);
        }

        if ($updateGTP) {
            return redirect()->to(base_url('dashboard/gtp'));
        } else {
            return redirect()->back()->withInput();
        }
    }
}
