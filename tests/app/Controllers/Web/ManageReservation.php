<?php

namespace App\Controllers\Web;

use App\Models\ReservationModel;
use App\Models\PackageDayModel;
use App\Models\AccountModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use DateTime;

class ManageReservation extends ResourcePresenter
{
    protected $reservationModel;
    protected $packageDayModel;
    protected $accountModel;
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->packageDayModel = new PackageDayModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $datareservation = $this->reservationModel->get_list_reservation()->getResultArray();

        foreach ($datareservation as &$item) { 
            $check_in = $item['check_in'];
            $getday = $this->packageDayModel->get_day_by_package($item['package_id'])->getResultArray();

            if(!empty($getday)){
                $totday = max($getday);
                $day = $totday['day'] - 1;
            }

            // Ubah $check_in menjadi objek DateTime untuk mempermudah perhitungan
            $check_in_datetime = new DateTime($check_in);
        
            if ($day == '0') {
                $item['check_out'] = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
            } else {
                // Tambahkan jumlah hari
                $check_in_datetime->modify('+' . $day . ' days');
                // Atur waktu selalu menjadi 12:00:00
                $item['check_out'] = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
            }
            $name_admin_confirm = $item['admin_confirm'];
            $getAdminC = $this->accountModel->get_profil_admin($item['admin_confirm'])->getRowArray();
            if($getAdminC!=null) {
                $item['name_admin_confirm'] =$getAdminC['username'];
            } else {
                $item['name_admin_confirm'] = 'adm';
            }

            $name_admin_refund = $item['admin_refund'];
            $getAdminR = $this->accountModel->get_profil_admin($item['admin_refund'])->getRowArray();
            if($getAdminR!=null) {
                $item['name_admin_refund'] =$getAdminR['username'];
            } else {
                $item['name_admin_refund'] = 'adm';
            }

            $admin_deposit_check = $item['admin_deposit_check'];
            $getAdminDP = $this->accountModel->get_profil_admin($item['admin_deposit_check'])->getRowArray();
            if($getAdminDP!=null) {
                $item['name_admin_deposit_check'] =$getAdminDP['username'];
            } else {
                $item['name_admin_deposit_check'] = 'adm';
            }

            $admin_payment_check = $item['admin_payment_check'];
            $getAdminFP= $this->accountModel->get_profil_admin($item['admin_payment_check'])->getRowArray();
            if($getAdminFP!=null) {
                $item['name_admin_payment_check'] =$getAdminFP['username'];
            } else {
                $item['name_admin_payment_check'] = 'adm';
            }
        }
        
        $data = [
            'title' => 'Manage Reservation',
            'data' => $datareservation,
        ];

        // dd($data);

        return view('dashboard/reservation', $data);
    }

    public function show($id = null)
    {
        $detail_reservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        if (empty($detail_reservation)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $detail_reservation['username'],
            'data' => $detail_reservation,
        ];

        // dd($data);
        // if (url_is('*dashboard*')) {
            return view('dashboard/detail_reservation', $data);
        // }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */

}
