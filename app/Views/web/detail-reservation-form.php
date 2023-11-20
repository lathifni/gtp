<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
$addhome = in_array('addhome', $uri);
$dashboard = in_array('dashboard', $uri);
?>

<?php if($dashboard): ?>
    <?= $this->extend('dashboard/layouts/main'); ?>
<?php else: ?>
    <?= $this->extend('web/layouts/main'); ?>
<?php endif; ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>
                <?php if(session()->has('warning')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Wait!',
                            text: '<?= session('warning') ?>',
                        });
                    </script>
                <?php endif; ?>
                <?php if(session()->has('success')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '<?= session('success') ?>',
                        });
                    </script>
                <?php endif; ?>
                <?php if(session()->has('failed')) : ?>
                    <script>
                        Swal.fire({
                            icon: 'danger',
                            title: 'Oops!',
                            text: '<?= session('failed') ?>',
                        });
                    </script>
                <?php endif; ?>

        <!-- Reservation Package -->
        <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Reservation Package</h4>
                        <?php if(in_groups(['admin'])): ?>
                            <?php if($data_package['type_name']=='Custom' && $detail['status']==null): ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('/web/detailreservation/packagecustom'); ?>/<?= esc($detail['package_id']); ?>" class="btn btn-outline-primary"><i class="fa-solid fa-pencil me-3"></i>Edit Package</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold">Package Name</td>
                                            <td><?= esc($data_package['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Type</td>
                                            <td><?= esc($data_package['type_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Request Date</td>
                                            <?php $request_date = strtotime($detail['request_date']); ?>
                                            <td><?= esc(date('l, j F Y H:i:s', $request_date)); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Days Package</td>
                                            <td><?= esc($daypack); ?> days</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Check In</td>
                                            <?php $check_in = strtotime($detail['check_in']); ?>
                                            <td><?= esc(date('l, j F Y H:i:s', $check_in)); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Check Out</td>
                                            <td><?= esc(date('l, j F Y H:i:s', strtotime($check_out))); ?></td>
                                        </tr>
                                        <tr>
                                        <td class="fw-bold">Min Capacity</td>
                                            <td><?= esc($data_package['min_capacity']); ?> orang</td>
                                        </tr>
                                        <td class="fw-bold">Total People</td>
                                            <td><?= esc($detail['total_people']); ?> orang</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Price</td>
                                            <td><?= 'Rp ' . number_format(esc($data_package['price']), 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Total Price Package</td>
                                            <?php 
                                            if($data_package['min_capacity']>0){
                                                if($detail['total_people']>$data_package['min_capacity']){
                                                    $jumlah_package = floor($detail['total_people']/$data_package['min_capacity']);
                                                    $tambahan =$detail['total_people']%$data_package['min_capacity'];
                                                } elseif($detail['total_people']<=$data_package['min_capacity']){
                                                    $jumlah_package = 1;
                                                    $tambahan = 0;
                                                }

                                                if($tambahan!=0){
                                                    if ($tambahan <5){
                                                        $order= $jumlah_package+0.5;
                                                    } else {
                                                        $order= $jumlah_package+1;
                                                    }
                                                } elseif ($tambahan==0) {
                                                    $order= $jumlah_package;
                                                }
                                                $total_price_package = $order*$data_package['price'];
                                            ?>
                                                <td><?= 'Rp ' . number_format(esc($total_price_package), 0, ',', '.'); ?></td>
                                            <?php 
                                            } else {
                                                ?>
                                                <td>Price not yet determined</td>
                                                <?php 
                                            }
                                            ?>
                                        </tr>
                                        <td class="fw-bold">Note</td>
                                            <td><?= esc($detail['note']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="accordion" id="accordionDetails">
                            <!-- Description -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="descriptionHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescription" aria-expanded="true" aria-controls="collapseDescription">
                                        Description
                                    </button>
                                </h2>
                                <div id="collapseDescription" class="accordion-collapse collapse" aria-labelledby="descriptionHeading">
                                    <div class="accordion-body">
                                        <!-- <p class="fw-bold">Description</p> -->
                                        <p><?= esc($data_package['description']);?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="serviceHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseService" aria-expanded="true" aria-controls="collapseService">
                                        Service
                                    </button>
                                </h2>
                                <div id="collapseService" class="accordion-collapse collapse" aria-labelledby="serviceHeading">
                                    <div class="accordion-body">
                                        <p class="fw-bold">Service Include</p>
                                        <?php foreach ($serviceinclude as $ls) : ?>
                                            <li><?= esc($ls['name']);?></li>
                                        <?php endforeach; ?>
                                        <br>
                                        <p class="fw-bold">Service Exclude</p>
                                        <?php foreach ($serviceexclude as $ls) : ?>
                                            <li><?= esc($ls['name']);?></li>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="activityHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseActivity" aria-expanded="true" aria-controls="collapseActivity">
                                        Activity
                                    </button>
                                </h2>
                                <div id="collapseActivity" class="accordion-collapse collapse" aria-labelledby="activityHeading">
                                    <div class="accordion-body">
                                        <!-- <p class="fw-bold">Activity</p> -->
                                        <?php foreach ($day as $d) : ?>
                                            <b>Day <?= esc($d['day']);?></b><br> 
                                            <?php foreach ($activity as $ac) : ?>
                                                <?php if($d['day']==$ac['day']): ?>
                                                    <?= esc($ac['activity']);?>. <?= esc($ac['name']);?> : <?= esc($ac['description']);?> <br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
        </div>

        <!-- Reservation Homestay -->
        <?php if($dayhome>0): ?>
            <div class="col-md-6 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center"><?= $title; ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if($addhome && $detail['status']==null): ?>
                        <div class="col-auto ">
                            <br>
                            <div class="btn-group float-right" role="group">
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#unitHomestayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> Add Unit Homestay</button>
                                
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" data-bs-whatever="@getbootstrap"><i class="fa fa-info"></i><i>Read this guide</i></button>
                                <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Reservation Guide</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <b>Reservasi Homestay</b>
                                                <li>Homestay dapat dipilih sesuai dengan keinginan user</li>
                                                <li>Informasi detail unit homestay ada pada halaman homestay</li>
                                                <li>Jumlah hari reservasi homestay menyesuaikan jumlah hari aktivitas pada paket wisata</li>
                                                <li>Jika unit homestay yang dipesan sudah dibooking maka akan muncul notifikasi 'unit homestay sudah dibooking' ketika ditambahkan</li>
                                                <li>Jika wisatawan hanya ingin memesan homestay, lakukan kustomisasi package dengan memilih aktivitas package hanya homestay yang dituju</li>
                                            <br>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Homestay</th>
                                    <th>Capacity</th>
                                    <th>Price</th>
                                    <?php if($addhome && $detail['status']==null): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if (isset($booking)) : ?> 
                                <?php $i = 1; ?>                     
                                <?php foreach ($booking as $dtb) : ?>
                                    <tr>
                                        <td><?= esc($i++); ?></td>
                                        <td>[<?= esc($dtb['name']); ?>] <?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                                        <td><?= esc($dtb['capacity']); ?></td>
                                        <td><?= esc($dtb['price']); ?></td>
                                        <?php if($addhome): ?>
                                        <td> 
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <form action="<?= base_url('web/detailreservation/deleteunit/').$dtb['homestay_id']; ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="date" value="<?= esc($dtb['date']); ?>">
                                                    <input type="hidden" name="homestay_id" value="<?= esc($dtb['homestay_id']); ?>">
                                                    <input type="hidden" name="unit_type" value="<?= esc($dtb['unit_type']); ?>">
                                                    <input type="hidden" name="unit_number" value="<?= esc($dtb['unit_number']); ?>">
                                                    <input type="hidden" name="reservation_id" value="<?= esc($dtb['reservation_id']); ?>">
                                                    <input type="hidden" name="description" value="<?= esc($dtb['description']); ?>">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-sm" onclick="return confirm('apakah anda yakin akan menghapus?');"><i class="fa fa-remove" aria-hidden="true"></i></button>
                                                </form>
                                            </div>
                                        </td> 
                                        <?php endif; ?>
                                    </tr>              
                                <?php endforeach; ?>
                            <?php endif; ?>
                                    <tr>
                                        <td>Total Day </td>
                                        <td>:   <?= esc($dayhome); ?> days</td>
                                    </tr>
                                    <tr>
                                        <td>Total Price Homestay </td>
                                        <td>:   <?= 'Rp' . number_format(esc($price_home), 0, ',', '.'); ?></td>
                                    </tr>                                
                            </tbody>
                        </table>
                        
                    <!-- modal add unit homestay -->
                        <div class="modal fade" id="unitHomestayModal" tabindex="-1" aria-labelledby="unitHomestayModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="unitHomestayModalLabel">Unit Homestay</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= base_url('web/detailreservation/create'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        
                                                        <label for="reservation_id">Reservation</label>
                                                        <input type="text" class="form-control" id="reservation_id" name="reservation_id" readonly value="<?= esc($detail['id']) ?>">
                                                        <input type="hidden" class="form-control" id="check_in_timestamp" name="check_in_timestamp" readonly value="<?= esc($detail['check_in']); ?>">
                                                        <input type="hidden" class="form-control" id="check_out_timestamp" name="check_out_timestamp" readonly value="<?= esc($check_out); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="pk_unit">Unit Homestay</label>
                                                    <select class="form-select" name="pk_unit" required>
                                                        <?php foreach ($list_unit as $item => $keyy) : ?>
                                                            <option value="<?= esc($keyy['homestay_id']); ?>-<?= esc($keyy['unit_type']); ?>-<?= esc($keyy['unit_number']); ?>"> 
                                                                [<?= esc($keyy['name']); ?>] <?= esc($keyy['name_type']); ?> <?= esc($keyy['unit_number']); ?> <?= esc($keyy['nama_unit']); ?>
                                                            </option>                                                                
                                                        <?php endforeach; ?>                                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                        <!-- <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button> -->
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    <!-- end modal add unit homestay -->

                    <?php if($addhome): ?>
                        <div class="col-auto">
                            <a href="<?= base_url('/web/reservation'); ?>" class="btn btn-outline-success float-end"><i class="fa-solid fa-check me-3"></i>Done</a>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <!-- payment -->
        <div class="col-md-12 col-12" >
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Payment</h4>
                    <?php if (in_groups(['admin'])) : ?>
                        <?php if(($detail['status'])==null): ?>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                    <i class="fa-solid fa-envelope me-3"></i>Confirmation
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>


                    <br>
                    <?php if (($detail['status'])!=null) : ?>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <?php if($detail['proof_of_deposit']!=null): ?>
                                <div class="gallery col-auto btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#depositModal"><i class="fa fa-money me-3"></i>
                                    <b>Proof of Deposit</b>
                                </div>
                            <?php endif; ?>
                            <?php if($detail['proof_of_payment']!=null): ?>
                                <div class="gallery col-auto btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentModal"><i class="fa fa-money me-3"></i>
                                    <b>Proof of Full Payment</b>
                                </div> 
                            <?php endif; ?>           
                            <?php if($detail['proof_refund']!=null): ?>
                                <div class="gallery col-auto btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cgalleryModal"><i class="fa fa-money me-3"></i>
                                    <b>Proof of Refund</b>    
                                </div>
                            <?php endif; ?>       
                            <?php if($detail['status']!='0' && $detail['cancel']!='1'): ?>       
                                <div class="col-auto">
                                    <a href="<?= base_url('/web/generatepdf/'); ?>/<?= esc($detail['id']); ?>" class="btn btn-outline-success"><i class="fa-solid fa-download me-3"></i>Download Invoice</a>
                                </div>
                            <?php endif; ?> 
                            <?php if($detail['cancel']=='1' && $detail['account_refund']!=null): ?>       
                                <div class="col-auto">
                                    <a href="<?= base_url('/web/generatepdf/'); ?>/<?= esc($detail['id']); ?>" class="btn btn-outline-success"><i class="fa-solid fa-download me-3"></i>Download Invoice</a>
                                </div>
                            <?php endif; ?> 
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div>
                        <table class="col-12">
                            <tbody>
                                <tr>
                                    <td><b>Total Reservation</b></td>
                                    <td><b>:   <?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></b></td>
                                </tr>
                                <tr>
                                    <td><b>Deposit Reservation</b></td>
                                    <td><b>:   <?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></b></td>
                                </tr>
                                <tr>
                                    <?php if($data_package['custom']==1 && $data_package['price']==0): ?>
                                        <i class="btn btn-sm btn-secondary">Please wait, the package price will be confirmed by admin </i>
                                    <?php elseif($data_package['custom']==1 && $data_package['price']!=0 && $detail['response']==null): ?>
                                        <p class="btn btn-secondary btn-sm"><i><b>Give your response on the package information that the admin has confirmed</b></i></p>
                                        <div class="col-auto">
                                            <button type="button" class="btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#responseModal">
                                                Send response
                                            </button>
                                        </div>
                                        <br>
                                        <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="responseModalLabel">Response About Package</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-header">
                                                            <form class="row g-4" id="responseform" action="<?= base_url('web/detailreservation/saveresponse/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                                                <?php @csrf_field(); ?>
                                                                <div class="form-group">
                                                                    <label for="response" class="mb-2">Response</label>
                                                                    <textarea class="form-control" id="response" name="response" cols="30" rows="5"  placeholder="Give your response about package, after that admin will confirmation accept or reject the reservation" required rows="4"><?= ($edit) ? $data['response'] : old('response'); ?></textarea>
                                                                </div>                                             
                                                                <div col="col-md-5 col-12">
                                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($data_package['custom']==1 && $data_package['price']!=0 && $detail['response']!=null): ?>
                                        <td>Response about the package </td>
                                        <td> : <?= $detail['response'];  ?> </td>
                                    <?php endif; ?>
                                </tr>
                                <tr>
                                    <?php if($detail['status']=='1' || $detail['status']=='0'): ?> 
                                        <td> Feedback admin about reservation</td>
                                        <td> : <?= esc($detail['feedback']); ?></td> 
                                    <?php endif; ?>   
                                </tr>
                                <tr>
                                    <?php if($detail['status']=='1' && $detail['cancel']=='0'): ?> 
                                        <td>
                                            <br>
                                            <p> Pembayaran melalui 
                                                <ul>
                                                    <li>Bank Syariah Mandiri (BSI) - Kode 451</li>
                                                    <li>Nomor rekening:  73492379</li>
                                                    <li>Atas nama: Green Talao Park</li>
                                                </ul>
                                            </p>
                                        </td> 
                                    <?php endif; ?>  
                                </tr>
                                <tr>
                                    <td><hr> </td>
                                    <td><hr> </td>
                                </tr>
                            <?php if(!$addhome):  ?>
                                <tr>
                                    <td> Status  </td>
                                    <td> : 
                                        <?php if($detail['status']==null ): ?>    
                                            <?php if($detail['custom']=='1' ): ?>
                                                <a href="#" class="btn-sm btn-warning float-center"><i>Negotiate</i></a>
                                            <?php elseif($detail['custom']!='0' ): ?>
                                                <a href="#" class="btn-sm btn-warning float-center"><i>Waiting</i></a>
                                            <?php endif; ?>
                                        <?php elseif($detail['status']=='1' ): ?>    
                                            <?php if($detail['cancel']=='0'): ?>
                                                <?php if($detail['proof_of_deposit']==null) :?>
                                                    <a href="#" class="btn-sm btn-info float-center"><i>Pay deposit</i></a>
                                            
                                                <?php elseif($detail['proof_of_deposit']!=null && $detail['proof_of_payment']==null): ?>
                                                    <a href="#" class="btn-sm btn-info float-center"><i>Pay in full</i></a>
                                                
                                                <?php elseif($detail['proof_of_deposit']!=null && $detail['proof_of_payment']!=null ):  ?>
                                                    <?php if($detail['review']==null): ?>
                                                        <a href="#" class="btn-sm btn-info float-center"><i>Unreviewed</i></a>
                                                
                                                    <?php else: ?>
                                                        <a href="#" class="btn-sm btn-success float-center"><i>Done</i></a>
                                                    
                                                    <?php endif; ?>        
                                                <?php endif; ?>
                                            <?php elseif($detail['cancel']=='1'): ?>
                                                <?php if($detail['account_refund']==null): ?>
                                                    <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel</i></a>

                                                <?php elseif($detail['account_refund']!=null && $detail['proof_refund']==null): ?>
                                                    <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel & refund</i></a>

                                                <?php elseif($detail['account_refund']!=null && $detail['proof_refund']!=null): ?>
                                                    <a href="#" class="btn-sm btn-danger float-center"><i>Refund</i></a>

                                                <?php endif; ?>

                                            <?php endif; ?>

                                        <?php elseif($detail['status']==0): ?>    
                                            <a href="#" class="btn-sm btn-danger float-center"><i>Rejected</i></a>
                                        
                                        <?php endif; ?>       
                                    </td>                            
                                </tr>
                            <?php endif; ?>
                                <tr>
                                    <?php if($detail['status']=='1' || $detail['status']=='0'): ?> 
                                        <td> Confirmation Date</td>
                                        <td> : <?= esc(date('l, j F Y H:i:s', strtotime($detail['confirmation_date']))); ?> (by adm<?= esc($detail['admin_confirm']); ?>)</td> 
                                    <?php endif; ?>   
                                </tr>
                                <tr>
                                    <?php if($detail['proof_of_deposit']!=null ): ?> 
                                        <td>Deposit Payment
                                                <td>
                                                    : <?= esc(date('l, j F Y H:i:s', strtotime($detail['deposit_date']))); ?>
                                                </td>
                                                
                                        </td>
                                    <?php endif; ?>  
                                </tr>
                                <tr>
                                    <?php if($detail['proof_of_payment']!=null): ?> 
                                        <td> Full Payment Reservation 
                                            <td>
                                                : <?= esc(date('l, j F Y H:i:s', strtotime($detail['payment_date']))); ?>
                                            </td>
                                        </td>                                                
                                    <?php endif; ?>   
                                </tr>
                                <tr>
                                    <?php if($detail['cancel_date']!=null): ?> 
                                        <td> Cancel Reservation 
                                            <td>
                                                : <?= esc(date('l, j F Y H:i:s', strtotime($detail['cancel_date']))); ?>
                                            </td>
                                        </td>                                                
                                    <?php endif; ?>   
                                </tr>
                                <tr>
                                    <?php if($detail['refund_date']!=null): ?> 
                                        <td> Refund Reservation 
                                            <td>
                                                : <?= esc(date('l, j F Y H:i:s', strtotime($detail['refund_date']))); ?> (by adm<?= esc($detail['admin_refund']); ?>)
                                            </td>
                                        </td>                                                
                                    <?php endif; ?>   
                                </tr>
                            </tbody>
                        </table>
                        
                        <table class="col-12">
                            <tbody>
                            <tr>
                                <!-- upload proof deposit -->
                                    <?php 
                                        $dateTime = new DateTime('now'); // Waktu sekarang
                                        $datenow = $dateTime->format('Y-m-d H:i:s'); 
                                    ?>
                                    <?php if($detail['status']=='1' && $detail['proof_of_deposit']==null && $detail['cancel']!=1 && $datenow<$batas_dp): ?>
                                        <p class="btn btn-sm btn-primary">Limit pay deposit : <?= esc(date('l, j F Y H:i:s', strtotime($batas_dp)));  ?></p>
                                        <br>
                                        <u><b>Countdown</b></u>
                                        <br><i>Upload before the deadline, if the deadline expires, the reservation is automatically canceled.</i>
                                        <h5 id="countdown"></h5>
                                        <script>
                                            // Set tanggal target countdown (dalam timestamp UNIX)
                                            var targetDate = <?php echo strtotime($batas_dp); ?>;

                                            // Fungsi untuk memperbarui countdown setiap detik
                                            function updateCountdown() {
                                                var currentDate = Math.floor(Date.now() / 1000);
                                                var remainingSeconds = targetDate - currentDate;

                                                if (remainingSeconds <= 0 && document.hasFocus()) {
                                                // if (remainingSeconds <= 0) {
                                                    document.getElementById('countdown').innerHTML = "Sorry, the deposit payment time for the reservation has expired";
                                                    clearInterval(countdownInterval);
                                                    
                                                            // Lakukan reload halaman setelah countdown habis
                                                    setTimeout(function() {
                                                        location.reload();
                                                    }, 9000); // Reload halaman setelah 3 detik
                                                    
                                                            // Lakukan submit form otomatis
                                                    document.querySelector('#cancelform').submit();
                                                } else {
                                                    var days = Math.floor(remainingSeconds / (24 * 60 * 60));
                                                    var hours = Math.floor((remainingSeconds % (24 * 60 * 60)) / (60 * 60));
                                                    var minutes = Math.floor((remainingSeconds % (60 * 60)) / 60);
                                                    var seconds = remainingSeconds % 60;

                                                    document.getElementById('countdown').innerHTML = days + " hari " + hours + " jam " + minutes + " menit " + seconds + " detik";
                                                }
                                            }

                                            var countdownInterval = setInterval(updateCountdown, 1000);
                                        </script>
                                        
                                        <form class="form form-vertical" action="<?= base_url('web/reservation/uploaddeposit/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                            <div class="form-body">
                                            <div class="col-md-5 col-12">
                                                    <div class="form-group mb-4">
                                                            <label for="proof_of_deposit" class="form-label">Proof of Deposit</label>
                                                            <input class="form-control" required accept="image/*" type="file" name="proof_of_deposit" id="proof_of_deposit" required>
                                                    </div>
                                                    </div>
                                                    <div col="col-md-5 col-12">
                                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <br>
                                        <p class="btn btn-secondary btn-sm"><i><b>Do you want to cancel? Cancel reservation can be made maximal H-3 check_in</b></i></p>
                                        <div class="col-auto">
                                            <button type="button" class="btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                                Yes, request
                                            </button>
                                        </div>
                                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="cancelModalLabel">Cancel</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-header">
                                                            <form class="row g-4" id="cancelform" action="<?= base_url('web/detailreservation/savecancel/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                                                <?php @csrf_field(); ?>
                                                                <div class="form-group">
                                                                    <label> Are you sure cancel this reservation? <br>
                                                                    <input type="radio" name="cancel" value="1" required>
                                                                    <i class="fa fa-check"></i> Yes
                                                                    </label>
                                                                </div>                                                  
                                                                <div col="col-md-5 col-12">
                                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($detail['status']==1 && $detail['proof_of_deposit']==null && $detail['cancel']!=1 && $datenow>$batas_dp ): ?>
                                        <p class="btn btn-danger btn-sm"><i><b>Upps Sorry, the deposit payment time for the reservation has expired</b></i></p>
                                        <br>
                                        <p class="btn btn-secondary btn-sm"><i><b>Do you want to cancel? Cancel reservation can be made maximal H-3 check_in</b></i></p>
                                        <form class="form hidden form-vertical" id="cancelform" action="<?= base_url('web/detailreservation/savecancel/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-body">
                                                <div class="col-md-5 col-12">
                                                    <div class="form-group mb-2">
                                                        <label>
                                                        <input type="radio" name="cancel" value="1" required>
                                                        <i class="fa fa-check"></i> Yes
                                                        </label>
                                                    </div>
                                                    <div col="col-md-5 col-12">
                                                        <button type="submit" class="btn btn-secondary me-1 mb-1">Cancel Reservation</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <script>
                                            window.onload = function() {
                                                document.querySelector('#cancelform').submit();
                                            };
                                        </script>
                                    <?php endif; ?>

                            <!-- upload proof payment -->
                                <?php if ($detail['status']=='1' && $detail['proof_of_deposit']!=null && $detail['cancel']!=1 && $detail['proof_of_payment']==null): ?>                                        
                                    <br>
                                    <i class="btn btn-primary btn-sm">Payment deadline in your check out time</i>
                                    <br> <br>
                                    <form class="form form-vertical" action="<?= base_url('web/reservation/uploadfullpayment/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                        <div class="form-body">
                                        <div class="col-md-5 col-12">
                                                    <div class="form-group mb-4">
                                                        <label for="proof_of_payment" class="form-label">  Proof of Full Payment</label>
                                                        <input class="form-control" required accept="image/*" type="file" name="proof_of_payment" id="proof_of_payment">
                                                    </div>
                                                </div>
                                                <div col="col-md-5 col-12">
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>    
                                    <br>    
                                <?php endif; ?>
                            </tr>

                            <tr>
                                <td>
                                    <!-- upload proof refund -->
                                    <?php if ($detail['status']==1 && $detail['proof_of_deposit']!=null && $detail['proof_of_payment']==null && $detail['cancel']!=1 && $batas_dp > $datenow ): ?>
                                        <i class="btn btn-secondary btn-sm"><b>Do you want to cancel reservation? </b></i> <br> <br>
                                            <button type="button" class="btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#refundModal">
                                                Yes, request
                                            </button>
                                    <?php endif; ?>

                                    <?php if($detail['cancel']=='1' && $detail['proof_of_deposit']!=null): ?>
                                        <br>
                                        <b>Account refund</b>
                                        <p><?= esc($detail['account_refund']); ?></p>
                                        <?php if($detail['proof_refund']==null): ?>
                                            
                                            <?php if (in_groups(['admin'])) : ?>
                                                <form class="form form-vertical" action="<?= base_url('dashboard/reservation/uploadrefund/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                    <div class="form-body">
                                                        <div class="col-md-5 col-12">
                                                            <div class="form-group mb-4">
                                                                    <label for="proof_refund" class="form-label">Proof of Refund</label>
                                                                    <input class="form-control" accept="image/*" type="file" name="proof_refund" id="proof_refund" required>
                                                            </div>
                                                            <div hidden class="form-group mb-2">
                                                                <label hidden for="refund_confirm" class="mb-2">Refund by</label>
                                                                <input type="number" readonly class="form-control" id="refund_confirm" name="refund_confirm" value="<?= user()->id; ?>" required rows="4"><?= ($edit) ? $data['refund_confirm'] : old('refund_confirm'); ?></input>
                                                            </div> 
                                                        </div>
                                                            <div col="col-md-5 col-12">
                                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <p><i>Refund belum dikirim</i></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>       
    
    <!-- confirm modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmModalModalLabel">Confirmation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-header">
                    <form class="row g-4"  action="<?= base_url('dashboard/detailreservation/saveconfirm/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                    <?php @csrf_field(); ?>
                        <div class="form-group">
                            <label for="confi" class="mb-2">Status Confirmation</label> <br>
                            <label>
                            <input type="radio" name="status" value="'0'" required>
                            <i class="fa fa-times"></i> Rejected
                            </label>
                            <label>
                            <input type="radio" name="status" value="1" required>
                            <i class="fa fa-check"></i> Accepted
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="feedback" class="mb-2">Feedback</label>
                            <textarea class="form-control" id="feedback" name="feedback" cols="30" rows="5"  placeholder="Isikan tanggapan terhadap reservasi" required rows="4"><?= ($edit) ? $data['feedback'] : old('feedback'); ?></textarea>
                        </div>                   
                        <div hidden class="form-group mb-2">
                            <label hidden for="admin_confirm" class="mb-2">Confirm by</label>
                            <input type="hidden" readonly class="form-control" id="admin_confirm" name="admin_confirm" value="<?= user()->id; ?>" required rows="4"><?= ($edit) ? $data['admin_confirm'] : old('admin_confirm'); ?></input>
                        </div>                                  
                        <div col="col-md-5 col-12">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- modal refund-->
    <div class="modal fade" id="cgalleryModal" tabindex="-1" role="dialog" aria-labelledby="cgalleryModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cgalleryModalTitle">
                        Proof of Refund
                    </h5>
                    
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="cGallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                                <button type="button" data-bs-target="#cGallerycarousel" data-bs-slide-to="<?= esc($i=1); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                        </div>
                        <div class="carousel-inner">
                            <?php $i = 0; ?>
                                <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                    <img class="d-block w-100" src="<?= base_url('media/photos/refund/'); ?><?= $detail['proof_refund'] ?>">
                                </div>
                        </div>
                        <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal request cancel and refund -->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="refundModalLabel">Cancel and Refund</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-header">
                    <form class="row g-4" action="<?= base_url('web/detailreservation/saverefund/').$detail['id']; ?>" method="post" enctype="multipart/form-data">
                        <?php @csrf_field(); ?>
                        <i class="btn btn-secondary btn-sm">Deposit will be returned only 50% of the deposit you sent. </i>

                        <div class="form-group">
                            <label> Are you sure cancel this reservation? <br>
                            <input type="radio" name="cancel" value="1" required>
                            <i class="fa fa-check"></i> Yes
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="account_refund" class="mb-2">Your bank account for refund</label>
                            <textarea class="form-control" id="account_refund" name="account_refund" cols="30" rows="5"  placeholder="Isikan akun bank penerima refund dengan detail (Nama bank, nomor rekening, dan nama pemilik akun)" required rows="4"><?= ($edit) ? $data['refund'] : old('refund'); ?></textarea>
                        </div>                                                    
                        <div col="col-md-5 col-12">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 </div>

    <!-- modal deposit -->
    <div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gdepositModalTitle">
                        Proof of Deposit
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                                <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i=1); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                        </div>
                        <div class="carousel-inner">
                            <?php $i = 0; ?>
                                <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                    <img class="d-block w-100" src="<?= base_url('media/photos/deposit/'); ?><?= $detail['proof_of_deposit'] ?>">
                                </div>
                        </div>
                        <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal payment -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gpaymentModalTitle">
                        Proof of Payment
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                                <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i=1); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                        </div>
                        <div class="carousel-inner">
                            <?php $i = 0; ?>
                                <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                    <img class="d-block w-100" src="<?= base_url('media/photos/fullpayment/'); ?><?= $detail['proof_of_payment'] ?>">
                                </div>
                        </div>
                        <a class="carousel-control-prev" href="#Gallerycarousel" role="button" type="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#Gallerycarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<?= $this->endSection() ?>