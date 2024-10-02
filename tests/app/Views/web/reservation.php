<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">

    <?php if(session()->has('success')) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= session('success') ?>',
            });
        </script>
    <?php endif; ?>
    <?php 
        $dateTime = new DateTime('now'); // Waktu sekarang
        $datenow = $dateTime->format('Y-m-d H:i:s'); 
    ?>
    <div class=" row">
    <div class="col-md-12">
        <div class="row">
            <!-- List Reservation -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">List Reservation</h5>
                    </div>
                    <div class="card-body">
                        <!-- <div class="row">

                            <div class="col-auto">
                                <a href="<?= current_url(); ?>/new" class="btn btn-primary float-right"><i class="fa-solid fa-plus me-3"></i>New Reservation</a>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('/web/package'); ?>" class="btn btn-secondary float-right">
                                    <i class="fa-solid fa-plus me-3"></i>Custom/ Extend Package for Booking
                                </a>
                                <br>
                            </div>
                        </div> -->
                        <br><br>
                        <div class="table-responsive">
                            <table class="table table-hover dt-head-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 20%;">Package Name</th>
                                        <th style="width: 20%;">Request Date</th>
                                        <th style="width: 20%;">Check In</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 25%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <?php if (isset($data)) : ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($data as $item) :?>
                                            <tr>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['name']);?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, H:i:s', strtotime($item['check_in'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');?>
                                                    <?php if($item['status']==null ): ?>    
                                                            <a href="#" class="btn-sm btn-warning float-center"><b>Waiting</b></a>
                                                    <?php elseif($item['status']=='1' ): ?>    
                                                        <?php if($item['cancel']=='0'): ?>
                                                            <?php if($item['proof_of_deposit']==null) :?>
                                                                <a href="#" class="btn-sm btn-info float-center"><b>Pay deposit!</b></a>
                                                        
                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']==null): ?>
                                                                <?php if($item['deposit_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Deposit Check</b></a>
                                                                <?php elseif($item['deposit_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Deposit Incorrect</b></a>
                                                                <?php elseif($item['deposit_check']==1): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Pay in full!</b></a>
                                                                <?php endif; ?>

                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']!=null ):  ?>
                                                                <?php if($item['payment_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Payment Check</b></a>
                                                                <?php elseif($item['payment_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Payment Incorrect</b></a>
                                                                <?php elseif($item['payment_check']==1): ?>

                                                                    <?php if($item['review']==null): ?>
                                                                        <?php if($datenow>=$item['check_out']): ?>
                                                                            <a href="#" class="btn-sm btn-dark float-center"><b>Unreviewed</b></a>
                                                                        <?php elseif($datenow<$item['check_out']): ?>
                                                                            <a href="#" class="btn-sm btn-dark float-center"><b>Enjoy trip!</b></a>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <a href="#" class="btn-sm btn-success float-center"><b>Done</b></a>
                                                                    <?php endif; ?>   

                                                                <?php endif; ?>       
                                                            <?php endif; ?>
                                                        <?php elseif($item['cancel']=='1'): ?>
                                                            <?php if($item['account_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><b>Cancel</b></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><b>Cancel & refund</b></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']!=null): ?>
                                                                <?php if($item['refund_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Refund Check</b></a>
                                                                <?php elseif($item['refund_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><b>Refund Incorrect</b></a>
                                                                <?php elseif($item['refund_check']==1): ?>
                                                                    <a href="#" class="btn-sm btn-danger float-center"><b>Refund Success</b></a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                    <?php elseif($item['status']==0): ?>    
                                                        <a href="#" class="btn-sm btn-danger float-center"><b>Rejected</b></a>
                                                    <?php endif; ?>  
                                                </td>
                                                <td>
                                                     <div class="btn-group" role="group" aria-label="Button Group">
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('web/detailreservation/').$item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i>
                                                        </a>
                                                        <a type="button" class="btn icon btn-outline-success mx-1" title="History" data-bs-toggle="modal" data-bs-target="#historyModal<?=esc($item['id'])?>" data-bs-whatever="@getbootstrap">
                                                            <i class="fa-solid fa-history"></i>
                                                        </a>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Review" class="btn icon btn-outline-info mx-1" href="<?=base_url('web/detailreservation/review/').$item['id']; ?>">
                                                            <i class="fa-solid fa-comments"></i>
                                                        </a>
                                                    </div>
                                                        <?php if($item['status']==null): ?>  
                                                            <form action="<?= base_url('web/reservation/delete/').$item['id']; ?>" method="post" class="d-inline" id="deleteForm<?= esc($item['id']) ?>">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?= esc($item['id']); ?>">
                                                                <input type="hidden" name="package_id" value="<?= esc($item['package_id']); ?>">
                                                                <input type="hidden" name="user_id" value="<?= esc($item['user_id']); ?>">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="button" class="btn icon btn-outline-danger " onclick="confirmDeletereservation('deleteForm<?= esc($item['id']) ?>')" ><i class="fa fa-trash"></i></button>
                                                            </form>

                                                        <?php else: ?>
                                                            <button type="submit" class="btn icon btn-outline-secondary" onclick="return showAlert();"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        <?php endif ?>

                                                     <!-- Modal Detail -->
                                                    <div class="modal fade" id="historyModal<?=esc($item['id'])?>" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="historyModalLabel">History Reservation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" id="historyContent">
                                                                    <div class="col-12  order-md-first order-last">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <br>
                                                                                <table class="col-12">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td> <b>Status</b>  </td>
                                                                                            <td> :
                                                                                                <?php $date = date('Y-m-d H:i');?>
                                                                                                <?php if($item['status']==null ): ?>    
                                                                                                        <a href="#" class="btn-sm btn-warning float-center"><b>Waiting</i></a>
                                                                                                <?php elseif($item['status']=='1' ): ?>    
                                                                                                    <?php if($item['cancel']=='0'): ?>
                                                                                                        <?php if($item['proof_of_deposit']==null) :?>
                                                                                                            <a href="#" class="btn-sm btn-info float-center"><b>Pay deposit!</i></a>
                                                                                                    
                                                                                                        <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']==null): ?>
                                                                                                            <?php if($item['deposit_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Deposit Check</i></a>
                                                                                                            <?php elseif($item['deposit_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Deposit Incorrect</i></a>
                                                                                                            <?php elseif($item['deposit_check']==1): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Pay in full!</i></a>
                                                                                                            <?php endif; ?>

                                                                                                        <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']!=null ):  ?>
                                                                                                            <?php if($item['payment_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Payment Check</i></a>
                                                                                                            <?php elseif($item['payment_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Payment Incorrect</i></a>
                                                                                                            <?php elseif($item['payment_check']==1): ?>

                                                                                                                <?php if($item['review']==null): ?>
                                                                                                                    <?php if($datenow>=$item['check_out']): ?>
                                                                                                                        <a href="#" class="btn-sm btn-dark float-center"><b>Unreviewed</i></a>
                                                                                                                    <?php elseif($datenow<$item['check_out']): ?>
                                                                                                                        <a href="#" class="btn-sm btn-dark float-center"><b>Enjoy trip!</i></a>
                                                                                                                    <?php endif; ?>
                                                                                                                <?php else: ?>
                                                                                                                    <a href="#" class="btn-sm btn-success float-center"><b>Done</i></a>
                                                                                                                <?php endif; ?>   

                                                                                                            <?php endif; ?>       
                                                                                                        <?php endif; ?>
                                                                                                    <?php elseif($item['cancel']=='1'): ?>
                                                                                                        <?php if($item['account_refund']==null): ?>
                                                                                                            <a href="#" class="btn-sm btn-secondary float-center"><b>Cancel</i></a>

                                                                                                        <?php elseif($item['account_refund']!=null && $item['proof_refund']==null): ?>
                                                                                                            <a href="#" class="btn-sm btn-secondary float-center"><b>Cancel & refund</i></a>

                                                                                                        <?php elseif($item['account_refund']!=null && $item['proof_refund']!=null): ?>
                                                                                                            <?php if($item['refund_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Refund Check</i></a>
                                                                                                            <?php elseif($item['refund_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><b>Refund Incorrect</i></a>
                                                                                                            <?php elseif($item['refund_check']==1): ?>
                                                                                                                <a href="#" class="btn-sm btn-danger float-center"><b>Refund Success</i></a>
                                                                                                            <?php endif; ?>
                                                                                                        <?php endif; ?>

                                                                                                    <?php endif; ?>

                                                                                                <?php elseif($item['status']==0): ?>    
                                                                                                    <a href="#" class="btn-sm btn-danger float-center"><b>Rejected</i></a>
                                                                                                <?php endif; ?>  
                                                                                            </td>                           
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['status']=='1' || $item['status']=='0'): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Confirmation Date</td>
                                                                                                <td> : <?= esc(date('l, j F Y H:i:s', strtotime($item['confirmation_date']))); ?> (by admin <?= esc($item['name_admin_confirm']); ?>)</td> 
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['status']=='1' || $item['status']=='0'): ?>  
                                                                                                <td> Feedback admin</td>
                                                                                                <td> : <?= esc($item['feedback']); ?> (by admin <?= esc($item['name_admin_confirm']); ?>)</td> 
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['proof_of_deposit']!=null ): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Deposit Payment
                                                                                                        <td>
                                                                                                            : <?= esc(date('l, j F Y H:i:s', strtotime($item['deposit_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                        </td>
                                                                                                        
                                                                                                </td>
                                                                                            <?php endif; ?>  
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['proof_of_deposit']!=null): ?>
                                                                                                <td>Status Deposit Payment
                                                                                                    <td>
                                                                                                        : 
                                                                                                        <?php if($item['deposit_check']==null ): ?> 
                                                                                                        We will check your proof of deposit 
                                                                                                        <?php elseif($item['deposit_check']==1 ): ?> 
                                                                                                        Thank you. The proof of deposit is correct
                                                                                                        <?php elseif($item['deposit_check']==0 ): ?> 
                                                                                                        Sorry. The proof of deposit is incorrect
                                                                                                        <?php endif; ?> 
                                                                                                        (by admin <?= esc($item['name_admin_deposit_check']); ?>)
                                                                                                    </td>
                                                                                                </td>
                                                                                            <?php endif; ?>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['proof_of_payment']!=null): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Full Payment Reservation 
                                                                                                    <td>
                                                                                                        : <?= esc(date('l, j F Y H:i:s', strtotime($item['payment_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                    </td>
                                                                                                </td>                                                
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['proof_of_payment']!=null): ?>
                                                                                                <td>Status FullPayment
                                                                                                    <td>
                                                                                                        : 
                                                                                                        <?php if($item['payment_check']==null ): ?> 
                                                                                                        We will check your proof of payment 
                                                                                                        <?php elseif($item['payment_check']==1 ): ?> 
                                                                                                        Thank you. The proof of payment is correct
                                                                                                        <?php elseif($item['payment_check']==0 ): ?> 
                                                                                                        Sorry. The proof of payment is incorrect
                                                                                                        <?php endif; ?> 
                                                                                                        (by admin <?= esc($item['name_admin_payment_check']); ?>)
                                                                                                    </td>
                                                                                                </td>
                                                                                            <?php endif; ?>  
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['cancel_date']!=null): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Cancel Reservation 
                                                                                                    <td>
                                                                                                        : <?= esc(date('l, j F Y H:i:s', strtotime($item['cancel_date']))); ?> (by <?= esc(user()->username); ?>)
                                                                                                    </td>
                                                                                                </td>                                                
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['account_refund']!=null && $item['cancel']==1): ?> 
                                                                                                <td> Status Cancel 
                                                                                                    <td>
                                                                                                        : Reservation has been cancelled (by <?= esc(user()->username); ?>)
                                                                                                    </td>
                                                                                                </td>
                                                                                            <?php elseif($item['account_refund']==null && $item['cancel']==1): ?> 
                                                                                                <td> Status Cancel 
                                                                                                    <td>
                                                                                                        : Admin will refund your payment (by admin <?= esc($item['name_admin_refund']); ?>)
                                                                                                    </td>
                                                                                                </td>                                                  
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['refund_date']!=null): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Refund Reservation 
                                                                                                    <td>
                                                                                                        : <?= esc(date('l, j F Y H:i:s', strtotime($item['refund_date']))); ?> (by adm <?= esc($item['name_admin_refund']); ?>)
                                                                                                    </td>
                                                                                                </td>                                                
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['refund_date']!=null): ?>
                                                                                                <td>Status Refund
                                                                                                    <td>
                                                                                                        : 
                                                                                                        <?php if($item['refund_check']==null ): ?> 
                                                                                                        You must check the proof of refund (by admin <?= esc($item['name_admin_refund']); ?>)
                                                                                                        <?php elseif($item['refund_check']==1 ): ?> 
                                                                                                        Thank you. The proof of refund is correct (by <?= esc($item['username']); ?>)
                                                                                                        <?php elseif($item['refund_check']==0 ): ?> 
                                                                                                        Sorry. The proof of refund is incorrect (by <?= esc($item['username']); ?>)
                                                                                                        <?php endif; ?> 
                                                                                                    </td>
                                                                                                </td>
                                                                                            <?php endif; ?> 
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($datenow<=$item['check_out'] && $item['review']==null && $item['cancel']==0 && $item['status']==1): ?> 
                                                                                                <td> Reservation Progress
                                                                                                    <td>
                                                                                                        : Reservation already, enjoy your trip
                                                                                                    </td>
                                                                                                </td>       
                                                                                            <?php elseif($datenow>=$item['check_out'] && $item['review']!=null && $item['cancel']==0 && $item['status']==1): ?> 
                                                                                                <td> Reservation Progress
                                                                                                    <td>
                                                                                                        : Your tour finished. Thank you for your review. See you on the next tour
                                                                                                    </td>
                                                                                                </td>       
                                                                                            <?php elseif($datenow>=$item['check_out'] && $item['proof_of_payment']!=null && $item['review']==null && $item['status']==1 && $item['cancel']==0): ?> 
                                                                                                <td> Reservation Progress
                                                                                                    <td>
                                                                                                        : You have finished your tour. Please give your review.
                                                                                                    </td>
                                                                                                </td>                                          
                                                                                            <?php endif; ?>   
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php $i++; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <script>
                                            boundToObject();
                                        </script>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>