<?php

namespace App\Controllers\Web;

use App\Models\GtpModel;
use Myth\Auth\Models\UserModel;
use App\Models\GalleryGtpModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\PackageModel;
use App\Models\FacilityModel;
use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\SouvenirPlaceModel;
use App\Models\ServicePackageModel;
use App\Models\HomestayModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $gtpModel;
    protected $userModel;
    protected $galleryGtpModel;
    protected $attractionModel;
    protected $eventModel;
    protected $packageModel;
    protected $facilityModel;
    protected $culinaryPlaceModel;
    protected $souvenirPlaceModel;
    protected $worshipPlaceModel;
    protected $servicePackageModel;
    protected $homestayModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->userModel = new UserModel();
        $this->galleryGtpModel = new GalleryGtpModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->packageModel = new PackageModel();
        $this->facilityModel = new FacilityModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->homestayModel = new HomestayModel();

    }
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];
        return view('dashboard/analytics', $data);
    }

    public function gtp()
    {
        $contents = $this->gtpModel->get_gtp()->getRowArray();

        $list_gallery = $this->galleryGtpModel->get_all_gallery()->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $contents['gallery'] = $galleries;

        $data = [
            'title' => 'Manage GTP Information',
            'data' => $contents,
            'folder' => 'gtp'
        ];
        return view('dashboard/manage-gtp', $data);
    }

    public function users()
    {
        $contentsAdmin = $this->userModel->get_admin()->getResultArray();
        $contentsCostumer = $this->userModel->get_users()->getResultArray();

        $data = [
            'title' => 'Manage Users',
            'manage' => 'Users',
            'adminData' => $contentsAdmin,
            'customerData' => $contentsCostumer,
        ];
        // DD($data);
        return view('dashboard/manage-users', $data);
    }

    public function attraction()
    {
        $contents = $this->attractionModel->get_list_attraction()->getResultArray();

        $data = [
            'title' => 'Manage Attraction',
            'manage' => 'Attraction',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function event()
    {
        $contents = $this->eventModel->get_list_event()->getResultArray();

        $data = [
            'title' => 'Manage Event',
            'manage' => 'Event',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function package()
    {
        $contents = $this->packageModel->get_list_package()->getResultArray();

        $data = [
            'title' => 'Manage Package',
            'manage' => 'Package',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function facility()
    {
        $contents = $this->facilityModel->get_list_facility()->getResultArray();

        $data = [
            'title' => 'Manage Facility',
            'manage' => 'Facility',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }
    
    
    public function culinaryplace()
    {
        $contents = $this->culinaryPlaceModel->get_list_cp()->getResultArray();

        $data = [
            'title' => 'Manage Culinary Place',
            'manage' => 'Culinary Place',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function souvenirplace()
    {
        $contents = $this->souvenirPlaceModel->get_list_sp()->getResultArray();

        $data = [
            'title' => 'Manage Souvenir Place',
            'manage' => 'Souvenir Place',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function worshipplace()
    {
        $contents = $this->worshipPlaceModel->get_list_wp()->getResultArray();

        $data = [
            'title' => 'Manage Worship Place',
            'manage' => 'Worship Place',
            'data' => $contents,
        ];
        return view('dashboard/manage-page', $data);
    }

    public function servicepackage()
    {
        $contents = $this->servicePackageModel->get_list_service_package()->getResultArray();
        $data = [
            'title' => 'Manage Service Package',
            'manage' => 'Service',
            'data' => $contents,
        ];

        return view('dashboard/manage-page', $data);
    }

    public function homestay()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResultArray();
        $data = [
            'title' => 'Manage Homestay',
            'manage' => 'Homestay',
            'data' => $contents,
        ];

        return view('dashboard/manage-page', $data);
    }
    
}
