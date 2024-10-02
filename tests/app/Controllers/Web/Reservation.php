<?php

namespace App\Controllers\Web;

use App\Models\ReservationModel;
use App\Models\DetailReservationModel ;
use App\Models\UnitHomestayModel;
use App\Models\PackageModel;
use App\Models\DetailPackageModel;
use App\Models\PackageDayModel;
use App\Models\AccountModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;
use DateTime;

class Reservation extends ResourcePresenter
{
    protected $reservationModel;
    protected $detailReservationModel ;
    protected $unitHomestayModel;
    protected $packageModel;
    protected $detailPackageModel;
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
        $this->detailReservationModel = new DetailReservationModel ();
        $this->detailPackageModel = new DetailPackageModel ();
        $this->unitHomestayModel = new UnitHomestayModel();
        $this->packageModel = new PackageModel();
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
        $user=user()->username;
        $datareservation = $this->reservationModel->get_list_reservation_by_user($user)->getResultArray();

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
            'title' => 'Reservation',
            'data' => $datareservation
        ];

        return view('web/reservation', $data);
    }

    public function report()
    {
        $datareservation_report = $this->reservationModel->get_list_reservation_report()->getResultArray();
        $deposit = $this->reservationModel->sum_done_deposit()->getRowArray();
        $total_price = $this->reservationModel->sum_done_total()->getRowArray();
        $dtrefund = $this->reservationModel->sum_done_refund()->getRowArray();
        $refund = $dtrefund['refund']/2;

        $data = [
            'title' => 'Report Reservation',
            'data' => $datareservation_report,
            'deposit' => $deposit['deposit'],
            'total_price' => $total_price['total_price'],
            'refund' => $refund
        ];
        // dd($data);
        return view('dashboard/reservation-report', $data);
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

        // if (url_is('*dashboard*')) {
            return view('web/detailreservation', $data);
        // }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        $id = $this->packageModel->get_new_id();
        $data = [
            'title' => 'New Reservation',
            'data' => $contents,
            'list_unit' => $list_unit,
            'custom_id' =>$id
        ];

        return view('web/reservation-form', $data);
    }

    public function custombooking($id)
    {
        $contents = $this->packageModel->get_package_by_id_custom($id)->getResultArray();
        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

        foreach ($contents as $con){
            if($con['days']==null) {
                $session = session();
                $session->setFlashdata('warning', 'Belum ada aktivitas package.');

                return redirect()->back();
            } else {
                $data = [
                    'title' => 'Reservation of Package',
                    'data' => $contents,
                    'list_unit' => $list_unit,
                ];
                
                return view('web/reservation-custom-form', $data);
            }
        }

    }

//     public function packagecustom()
//     {
//         $contents = $this->packageModel->get_list_package_distinct()->getResultArray();
// dd($contents);
//         $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

//         $data = [
//             'title' => 'Custom Package',
//             'data' => $contents,
//             'list_unit' => $list_unit,
//         ];

//         return view('web/custom-package-form', $data);
//     }

    public function dataunithomestay()
    {
            $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();

            // Periksa apakah ada hasil
            if ($list_unit > 0) {
                // Siapkan opsi untuk pilihan unit homestay
                $options = '<option value="">Select Unit Homestay</option>';
                while ($row = $list_unit->fetch_assoc()) {
                    $homestay_id = $row['homestay_id'];
                    $unit_type = $row['unit_type'];
                    $unit_number = $row['unit_number'];
                    $price = $row['price'];
                    $capacity = $row['capacity'];

                    // Tambahkan opsi ke variabel $options
                    $options .= "<option value='$homestay_id' data-price='$price'>$unit_type - $unit_number (Capacity: $capacity)</option>";
                }
                echo $options;
            } else {
                echo '<option value="">No Units Available</option>';
            }

        // $data = [
        //     'title' => 'Homestay',
        //     'data' => $list_unit,
        // ];

        // // dd($data);
        // return view('dashboard/reservation-form', $data);
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

        $id = $this->reservationModel->get_new_id();
        $date = date('Y-m-d H:i');
        $requestData = [
            'id' => $id,
            'user_id' => user()->id,
            'package_id' => $request['package'],
            'request_date' => $date,
            'total_people' => $request['total_people'],
            'check_in' => $request['check_in'].' '.$request['time_check_in'],
            // 'check_out' => $request['check_out'].' '.$request['time_check_out'],
            'total_price' => $request['total_price'],
            'deposit' => $request['deposit'],
            'note' => $request['note']
        ];
        // dd($requestData);
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addRe = $this->reservationModel->add_new_reservation($requestData);

        if ($addRe) {
            return redirect()->to(base_url('web/detailreservation/addhome/'.$id));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $contents = $this->packageModel->get_list_package()->getResultArray();

        $datareservation = $this->reservationModel->get_reservation_by_id($id)->getRowArray();

        $list_unit = $this->unitHomestayModel->get_unit_homestay_all()->getResultArray();
        
        if (empty($datareservation)) {
            return redirect()->to('web/detailreservation');
        }
        $date = date('Y-m-d');

        $data = [
            'title' => 'Reservation Homestay',
            'data' => $contents,
            'detail' => $datareservation,
            'list_unit' => $list_unit,
            'date'=>$date
        ];
        
        return view('web/reservation-form', $data);
    }

    // public function update($id = null)
    // {
    //     $request = $this->request->getPost();
    //     $requestData = [
    //         'id' => $id,
    //         'name' => $request['name'],
    //     ];
    //     foreach ($requestData as $key => $value) {
    //         if (empty($value)) {
    //             unset($requestData[$key]);
    //         }
    //     }

    //     $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

    //     if ($updateSP) {
    //         return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
    //     } else {
    //         return redirect()->back()->withInput();
    //     }
    // }


    public function uploaddeposit($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');
        
        $requestData = [
            'deposit_date' => $date,
            'deposit_check'=> null,
        ];

        foreach ($requestData as $key => $value) {
            if(empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_deposit');

        if (empty($_FILES['proof_of_deposit']['name'])) {
            $query = $this->reservationModel->upload_deposit($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload deposit. Please wait, we will check your the deposit proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload deposit"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_deposit' => [
                    'label' => 'proof_of_deposit File',
                    'rules' => 'uploaded[proof_of_deposit]'
                        . '|is_image[proof_of_deposit]'
                        . '|mime_in[proof_of_deposit,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_deposit']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit "
                    ]
                ];
                return $this->respond($response, 400);
            }
    
            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/deposit');
                $requestData['proof_of_deposit'] = $user_image->getFilename();
        
                $query = $this->reservationModel->upload_deposit($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload deposit image. Please wait, we will check your the deposit proof"
                        ]
                    ];
                    return redirect()->back();
                    
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload deposit"
                    ]
                ];
                return $this->respond($response, 400);
        
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload deposit."
            ]
        ];

        
        return $this->respond($response, 400);
    }

    public function uploadfullpayment($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'payment_date' => $date,
        ];
        foreach ($requestData as $key => $value) {
            if(empty($value)) {
                unset($requestData[$key]);
            }
        }
        $img = $this->request->getFile('proof_of_payment');

        if (empty($_FILES['proof_of_payment']['name'])) {
            $query = $this->reservationModel->upload_fullpayment($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Success upload full payment. Please wait, we will check your the payment proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload full payment"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_of_payment' => [
                    'label' => 'proof_of_payment File',
                    'rules' => 'uploaded[proof_of_payment]'
                        . '|is_image[proof_of_payment]'
                        . '|mime_in[proof_of_payment,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_of_payment']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment "
                    ]
                ];
                return $this->respond($response, 400);
            }
    
            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/fullpayment');
                $requestData['proof_of_payment'] = $user_image->getFilename();
        
                $query = $this->reservationModel->upload_fullpayment($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Success upload full payment image. Please wait, we will check your the payment proof"
                        ]
                    ];
                    return redirect()->back();
                    
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload full payment"
                    ]
                ];
                return $this->respond($response, 400);
        
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload fullpayment."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function uploadrefund($id = null)
    {
        $request = $this->request->getPost();
        $date = date('Y-m-d H:i');

        $requestData = [
            'refund_date' => $date,
            'admin_refund' => $request['admin_refund'],
            'refund_check' => null,
        ];
        $img = $this->request->getFile('proof_refund');

        if (empty($_FILES['proof_refund']['name'])) {
            $query = $this->reservationModel->upload_refund($id, $requestData);
            if ($query) {
                $response = [
                    'status' => 200,
                    'message' => [
                        "Successful upload refund. Please wait, the customer will check your refund proof"
                    ]
                ];
                return redirect()->back();
            }
            $response = [
                'status' => 400,
                'message' => [
                    "Fail upload refund"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $validationRule = [
                'proof_refund' => [
                    'label' => 'proof_refund File',
                    'rules' => 'uploaded[proof_refund]'
                        . '|is_image[proof_refund]'
                        . '|mime_in[proof_refund,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
            ];
            if (!$this->validate($validationRule) && !empty($_FILES['proof_refund']['name'])) {
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload refund "
                    ]
                ];
                return $this->respond($response, 400);
            }
    
            if ($img->isValid() && !$img->hasMoved()) {
                $filepath = WRITEPATH . 'uploads/' . $img->store();
                $user_image = new File($filepath);
                $user_image->move(FCPATH . 'media/photos/refund');
                $requestData['proof_refund'] = $user_image->getFilename();
        
                $query = $this->reservationModel->upload_refund($id, $requestData);
                if ($query) {
                    $response = [
                        'status' => 200,
                        'message' => [
                            "Successful upload refund. Please wait, the customer will check your refund proof"
                        ]
                    ];
                    return redirect()->back();
                    
                }
                $response = [
                    'status' => 400,
                    'message' => [
                        "Fail upload refund"
                    ]
                ];
                return $this->respond($response, 400);
        
            }
        }
        $response = [
            'status' => 400,
            'message' => [
                "Fail upload refund."
            ]
        ];
        return $this->respond($response, 400);
    }

    public function delete($id=null, $package_id=null, $user_id=null)
    {
        $request = $this->request->getPost();

        $id=$request['id'];
        $package_id=$request['package_id'];
        $user_id=$request['user_id'];

        $array1 = array('reservation_id' => $id);
        $detailReservation = $this->detailReservationModel->where($array1)->find();
        $deleteDR= $this->detailReservationModel->where($array1)->delete();

        if ($deleteDR) {
            //jika success
            $array2 = array('id' => $id, 'package_id' => $package_id,'user_id'=>$user_id);
            $reservation = $this->reservationModel->where($array2)->find();
            // dd($packageDay);
            $deleteRE= $this->reservationModel->where($array2)->delete();

            if($deleteRE){
                session()->setFlashdata('success', 'Reservation "'.$id.'" Deleted Successfully.');
                return redirect()->back();
            }
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }    
    }

}
