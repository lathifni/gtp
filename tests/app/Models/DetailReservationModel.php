<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'detail_reservation';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['data','package_id','unit_type','unit_number','reservation_id','review','rating'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function add_new_detail_reservation($detailreservation = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($detailreservation);
        return $insert;
    }

    public function checkIfDataExists($requestData)
    {
        return $this->table($this->table)
            ->where('date', $requestData['date'])
            ->where('unit_number', $requestData['unit_number'])
            ->where('homestay_id', $requestData['homestay_id'])
            ->where('unit_type', $requestData['unit_type'])
            ->get()
            ->getRow();
    }
    
    public function get_unit_homestay_booking($reservation_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->where('reservation_id', $reservation_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_bookingnya($reservation_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('date, unit_number, homestay_id, unit_type, reservation_id')
            ->where('reservation_id', $reservation_id)
            ->distinct()
            ->get();
        return $query;
    }

    public function get_unit_homestay_dtbooking($reservation_id =  null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            // ->join('homestay', 'homestay.id = detail_reservation.homestay_id')
            // ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type')
            ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();

        return $query;
    }

    public function get_unit_homestay_booking_data($date=null,$homestay_id=null, $unit_type=null, $unit_number=null,$reservation_id=null)
    {
        $query = $this->db->table($this->table)
        ->select('*')
        ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
        ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'inner')
        ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type', 'inner')
        ->where('detail_reservation.date', $date)
        ->where('unit_homestay.unit_number', $unit_number)
        ->where('unit_homestay.unit_type', $unit_type)
        ->where('unit_homestay.homestay_id', $homestay_id)
        ->where('detail_reservation.reservation_id', $reservation_id)
        ->get();
        return $query;
    }

    
    public function get_unit_homestay_booking_data_reservation($homestay_id=null, $unit_type=null, $unit_number=null,$reservation_id=null)
    {
        $query = $this->db->table($this->table)
        ->select('detail_reservation.reservation_id, detail_reservation.unit_number, detail_reservation.homestay_id, detail_reservation.unit_type, detail_reservation.review, detail_reservation.rating,
                unit_homestay.nama_unit, unit_homestay.description, unit_homestay.price, unit_homestay.capacity,
                homestay_unit_type.name_type, reservation.status, homestay.name, homestay.address')
        ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
        ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'inner')
        ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type', 'inner')
        ->join('reservation', 'reservation.id = detail_reservation.reservation_id', 'inner')
        ->where('unit_homestay.unit_number', $unit_number)
        ->where('unit_homestay.unit_type', $unit_type)
        ->where('unit_homestay.homestay_id', $homestay_id)
        ->where('detail_reservation.reservation_id', $reservation_id)
        ->distinct()
        ->get();
        return $query;
    }

    public function get_price_homestay_booking($homestay_id=null, $unit_type=null, $unit_number=null,$reservation_id=null)
    {
        $query = $this->db->table($this->table)
            ->select('price')
            ->join('unit_homestay', 'detail_reservation.homestay_id = unit_homestay.homestay_id', 'detail_reservation.unit_number = unit_homestay.unit_number', 'detail_reservation.unit_type = unit_homestay.unit_type')
            ->join('homestay', 'homestay.id = detail_reservation.homestay_id', 'inner')
            ->join('homestay_unit_type', 'homestay_unit_type.id = detail_reservation.unit_type', 'inner')
            ->where('unit_homestay.unit_number', $unit_number)
            ->where('unit_homestay.unit_type', $unit_type)
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('detail_reservation.reservation_id', $reservation_id)
            ->get();
            
        return $query;
    }

    public function update_detailreservation($reservation_id = null, $unit_number = null, $homestay_id = null, $unit_type = null, $data = null) {
        $query = $this->db->table('detail_reservation')
            ->update($data, ['reservation_id' => $reservation_id, 'unit_number' => $unit_number, 'homestay_id' => $homestay_id, 'unit_type' => $unit_type,]);
        return $query;
    }

    public function update_cancel($id = null, $data = null) {
        // $lastStatus = $this->db->table($this->table)->select('status')->like('status', '0', 'after')->orderBy('status', 'ASC')->get()->getLastRow('array');
        
        // if(empty($lastStatus)){
        //     $status='001';
        // }else{
        // $count = (int)substr($lastStatus['status'], 2);
        // $status = sprintf('0%02d', $count + 1);
        // }
        
        // $data['status'] = $status; // Mengatur kolom 'status' menjadi 0

        // $query = $this->db->table('detail_reservation')
        //                 ->set($data)
        //                 ->where('reservation_id', $id)
        //                 ->update();
        return $query;
    }
    
    public function getReview($unit_number = null, $homestay_id = null, $unit_type = null)
    {
        $query = $this->db->table($this->table)
            ->select("`detail_reservation.homestay_id`,`detail_reservation.unit_number`,`detail_reservation.unit_type`,`detail_reservation.review`, `detail_reservation.rating`, `users.username`")
            ->join('reservation', 'reservation.id = detail_reservation.reservation_id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.homestay_id', $homestay_id)
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('detail_reservation.rating <>', 0) 
            ->get();
        return $query;
    }
    
    public function getRating($unit_number = null, $homestay_id = null, $unit_type = null)
    {
        $query = $this->db->table($this->table)
            ->selectAVG('detail_reservation.rating', 'rating')
            ->select("`detail_reservation.homestay_id`,`detail_reservation.unit_number`,`detail_reservation.unit_type`")
            ->where('detail_reservation.unit_number', $unit_number)
            ->where('detail_reservation.homestay_id', $homestay_id)
            ->where('detail_reservation.unit_type', $unit_type)
            ->where('detail_reservation.rating <>', 0) 
            ->get();
        return $query;
    }

}
