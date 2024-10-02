<?php

namespace App\Controllers\Web;

use CodeIgniter\Controller;
// use TCPDF;
use App\Libraries\MY_TCPDF AS TCPDF;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use DateTime;

use App\Models\BackupDetailReservationModel;
use App\Models\DetailReservationModel;
use App\Models\ReservationModel;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
use App\Models\DetailServicePackageModel;

use App\Models\CulinaryPlaceModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\HomestayModel;
use App\Models\ServicePackageModel;
use App\Models\AccountModel;
use Myth\Auth\Models\UserModel;

class PdfController extends ResourcePresenter
{
    // protected $gtpModel;

    protected $backupDetailReservationModel;
    protected $detailReservationModel;
    protected $reservationModel;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $packageDayModel;
    protected $detailPackageModel;
    protected $detailServicePackageModel;
    protected $culinaryPlaceModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $homestayModel;
    protected $servicePackageModel;
    protected $accountModel;
    protected $userModel;
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        // $this->gtpModel = new GtpModel();
        // $this->galleryGtpModel = new GalleryGtpModel();

        $this->backupDetailReservationModel = new BackupDetailReservationModel();
        $this->detailReservationModel = new DetailReservationModel();
        $this->reservationModel = new ReservationModel();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->homestayModel = new HomestayModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->accountModel = new AccountModel();
        $this->userModel = new UserModel();

    }

    public function generatePDF($id=null)
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Desa Wisata Green Talao Park');
        $pdf->SetTitle('PDF Invoice Desa Wisata Green Talao Park');
        $pdf->SetSubject('Desa Wisata Green Talao Park');
        $pdf->SetKeywords('TCPDF, PDF, invoice, desawisatagtp.online');


        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);


        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();
        $package_id_reservation= $datareservation['package_id'];
        
        //detail package 
        $package = $this->packageModel->get_package_by_id($package_id_reservation)->getRowArray();        
        $serviceinclude= $this->detailServicePackageModel->get_service_include_by_id($package_id_reservation)->getResultArray();
        $serviceexclude= $this->detailServicePackageModel->get_service_exclude_by_id($package_id_reservation)->getResultArray();
        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id_reservation)->getResultArray();
        $getday = $this->packageDayModel->get_day_by_package($package_id_reservation)->getResultArray();
        $combinedData = $this->detailPackageModel->getCombinedData($package_id_reservation);

        if(!empty($getday)){
            $day=max($getday);
            $daypack=$day['day'];
            $dayhome=$day['day']-1;
        } else {
            $day=1;
            $daypack=1;
            $dayhome=0;
        }
        
        //data homestay
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        if($datareservation['cancel']=='0'){
            $booking_unit = $this->detailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        } else if ($datareservation['cancel']=='1'){
            $booking_unit = $this->backupDetailReservationModel->get_unit_homestay_bookingnya($id)->getResultArray();
        }
        
        // $unit_booking= $this->detailReservationModel->get_unit_homestay_dtbooking($id)->getResultArray();

        // dd($booking_unit);
        if(!empty($booking_unit)){
            $data_unit_booking=array();
            $data_price=array();
            foreach($booking_unit as $booking){
                $date=$booking['date'];
                $homestay_id=$booking['homestay_id'];
                $unit_type=$booking['unit_type'];
                $unit_number=$booking['unit_number'];
                $reservation_id=$booking['reservation_id'];

                if($datareservation['cancel']=='0'){
                    $unit_booking[] = $this->detailReservationModel->get_unit_homestay_booking_data($date,$homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                    $total_price_homestay = $this->detailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                } else if ($datareservation['cancel']=='1'){
                    $unit_booking[] = $this->backupDetailReservationModel->get_unit_homestay_booking_data($date,$homestay_id,$unit_type,$unit_number,$id)->getRowArray();
                    $total_price_homestay = $this->backupDetailReservationModel->get_price_homestay_booking($homestay_id,$unit_type,$unit_number,$id)->getRow();
                }
                
                $total []= $total_price_homestay->price;
            }

            $data_price=$total;
            // dd($data_price);

            $tphom = array_sum($data_price);
            $tph=$tphom*$dayhome;
            // $tph = array_sum($data_price);
            $data_unit_booking=$unit_booking;


        } else{
            $data_unit_booking=[];
            $tph = '0';
        }

        // $check_in = "2023-10-29 11:51:00";
        $check_in = $datareservation['check_in'];
        if(!empty($getday)){
            $totday = max($getday);
            $day = $totday['day'] - 1;
        } else {
            $totday=1;
            $day=$totday-1;
        }

        // Ubah $check_in menjadi objek DateTime 
        $check_in_datetime = new DateTime($check_in);

        if($day=='0'){
            $check_out = $check_in_datetime->format('Y-m-d') . ' 18:00:00';
        } else {
            // Tambahkan jumlah hari
            $check_in_datetime->modify('+' . $day . ' days');
            // Atur waktu selalu menjadi 12:00:00
            $check_out = $check_in_datetime->format('Y-m-d') . ' 12:00:00';
        }


            $name_admin_confirm = $datareservation['admin_confirm'];
            $getAdminC = $this->accountModel->get_profil_admin($datareservation['admin_confirm'])->getRowArray();
            if($getAdminC!=null) {
                $datareservation['name_admin_confirm'] =$getAdminC['username'];
            } else {
                $datareservation['name_admin_confirm'] = 'adm';
            }

            $name_admin_refund = $datareservation['admin_refund'];
            $getAdminR = $this->accountModel->get_profil_admin($datareservation['admin_refund'])->getRowArray();
            if($getAdminR!=null) {
                $datareservation['name_admin_refund'] =$getAdminR['username'];
            } else {
                $datareservation['name_admin_refund'] = 'adm';
            }

            $admin_deposit_check = $datareservation['admin_deposit_check'];
            $getAdminDP = $this->accountModel->get_profil_admin($datareservation['admin_deposit_check'])->getRowArray();
            if($getAdminDP!=null) {
                $datareservation['name_admin_deposit_check'] =$getAdminDP['username'];
            } else {
                $datareservation['name_admin_deposit_check'] = 'adm';
            }

            $admin_payment_check = $datareservation['admin_payment_check'];
            $getAdminFP= $this->accountModel->get_profil_admin($datareservation['admin_payment_check'])->getRowArray();
            if($getAdminFP!=null) {
                $datareservation['name_admin_payment_check'] =$getAdminFP['username'];
            } else {
                $datareservation['name_admin_payment_check'] = 'adm';
            }

        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $user_id = $datareservation['user_id'];
        $us = $this->userModel->get_users_by_id($user_id)->getRowArray();

        $data = [
            //data package
            'data_package' => $package,
            'serviceinclude' => $serviceinclude,
            'serviceexclude' => $serviceexclude,
            'day'=> $getday,
            'daypack'=> $daypack,
            'activity' => $combinedData,
            'detail' => $datareservation,
            'customer' => $us,

            //data homestay
            'data' => $contents,
            'list_unit' => $list_unit,
            'date'=>$date,            
            'dayhome'=> $dayhome,
            'check_out'=>$check_out,
            'data_unit'=>$booking_unit,
            'booking'=>$data_unit_booking,
            'price_home'=>$tph
        ];
        // return view('web/invoice', $data);

               //view mengarah ke invoice.php
               $html = view('web/invoice', $data);

               // Print text using writeHTMLCell()
               $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
       
               // ---------------------------------------------------------
               $this->response->setContentType('application/pdf');
               // Close and output PDF document
               // This method has several options, check the source code documentation for more information.
               $pdf->Output('invoice-pos-sobatcoding.pdf', 'I');
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
       
    }

    public function edit($id = null)
    {
        
    }

    public function update($id = null)
    {
       
    }
}
