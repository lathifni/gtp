<?php

namespace App\Controllers\Web;
use App\Models\PackageDayModel;
use App\Models\PackageModel;
use App\Models\DetailPackageModel;

use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class PackageDay extends ResourcePresenter
{

    use ResponseTrait;

    protected $packageDayModel;
    protected $packageModel;
    protected $detailPackageModel;
    protected $culinaryPlaceModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $db, $builder;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->packageDayModel = new PackageDayModel();
        $this->packageModel = new PackageModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('detail_package', 'culinary_place');;
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
        $sp = $this->packageDayModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_servicepackage', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function newday($id)
    {        
        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        $package_id=$package['id'];
        $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

        $culinary = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $worship = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $facility = $this->facilityModel->get_list_facility()->getResultArray();
        $souvenir = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();
        $event = $this->eventModel->get_list_event()->getResultArray();

        $data_object = array_merge($culinary,$worship,$facility,$souvenir,$attraction,$event);

        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id)->getResultArray();
        
        $combinedData = $this->detailPackageModel->getCombinedData();
            
        $object = [
            'culinary' => $culinary,
            'worship' => $worship,
            'facility' => $facility,
            'souvenir' => $souvenir,
            'attraction' => $attraction,
            'event' => $event
        ];

        $data = [
            'title' => 'Detail Package '.$package['name'],
            'data' => $package,
            'day' => $packageDay,
            'activity' => $detailPackage,
            'data_package' => $combinedData,
            'object' => $object
        ];  
        // dd( $data);

        return view('dashboard/detail-package-form', $data, $object);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function createday($id)
    {

        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'description' => $request['description']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addPD = $this->packageDayModel->add_new_packageDay($requestData);

        if ($addPD) {
            // return view('dashboard/detail-package-form');
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();

            $id=$package['id'];
            $data = [
                'title' => 'New Detail Package',
                'data' => $package
            ];
            
            // return view('dashboard/detail-package-form', $data);

            return redirect()->to(base_url('dashboard/packageday/').$id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createactivity($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'activity' => $request['activity'],
            'activity_type' => $request['activity_type'],
            'object_id' => $request['object'],
            'description' => $request['description_activity']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addPA = $this->detailPackageModel->add_new_packageActivity($requestData);

        if ($addPA) {
            // return view('dashboard/detail-package-form');
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();

            $id=$package['id'];
            $data = [
                'title' => 'New Detail Package',
                'data' => $package
            ];
            
            // return view('dashboard/detail-package-form', $data);

            return redirect()->to(base_url('dashboard/packageday/').$id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $sp = $this->packageDayModel->get_servicePackage_by_id($id)->getRowArray();
        if (empty($sp)) {
            return redirect()->to('dashboard/service-package');
        }

        $servicePackage = $this->packageDayModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'Edit Service Package',
            'data' => $sp,
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
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

        $updateSP = $this->packageDayModel->update_servicePackage($id, $requestData);

        if ($updateSP) {
            return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    // public function delete($package_id = null, $day=null, $activity=null)
    // {
    //     $id=$package_id;
    //     $deleteDP = $this->detailPackageModel->delete(['package_id' => $id]);

    //     dd($deleteDP);
        // if ($deleteDP) {
        //     $response = [
        //         'status' => 200,
        //         'message' => [
        //             "Success delete Activity Package Day"
        //         ]
        //     ];
        //     return $this->respondDeleted($response);
        // }
    // }


    public function delete($package_id=null, $day=null, $activity=null, $description=null)
    {
        $request = $this->request->getPost();

        $day=$request['day'];
        $activity=$request['activity'];
        $description=$request['description'];

        $array = array('package_id' => $package_id, 'day' => $day, 'activity' => $activity);
        $detailPackage = $this->detailPackageModel->where($array)->find();
        $deleteDP= $this->detailPackageModel->where($array)->delete();

        if ($deleteDP) {
            session()->setFlashdata('pesan', 'Activity "'.$description.'" Berhasil di Hapus.');
            //jika success
            $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();

            $package_id=$package['id'];
            
            $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

            // dd($packageDay);
            // foreach ($packageDay as $item):
                // $dayp=$item['day'];
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();
            
                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package,
                    'day' => $packageDay,
                    'activity' => $detailPackage
                ];  

            // endforeach;
            return redirect()->to(base_url('dashboard/packageday') . '/' . $package_id);

            // return view('dashboard/detail-package-form', $data, $package, $packageDay, $detailPackage);

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
    }

        
        // return redirect()->to('/packageday/P0014');
    }
}
