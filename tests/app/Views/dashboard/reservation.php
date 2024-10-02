<?php
$uri = service('uri')->getSegments();
$users = in_array('users', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>
<?php 
    $dateTime = new DateTime('now'); // Waktu sekarang
    $datenow = $dateTime->format('Y-m-d H:i:s'); 
?>
<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <div class="col-md-12">
        <div class="row">
            <!-- List Reservation -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center"><?= esc($title); ?></h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover dt-head-center" id="table-manage">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Costumer</th>
                                        <th>Package Name</th>
                                        <th>Request Date</th>
                                        <th>Check In</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                    <?php if (isset($data)) : ?>
                                        <?php $i = 1; ?>
                                        <?php foreach ($data as $item) :?>
                                            <tr>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['id']); ?></td>
                                                <td><?= esc($item['username']); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['request_date'])); ?></td>
                                                <td><?= date('d F Y, h:i:s A', strtotime($item['check_in'])); ?></td>
                                                <td>
                                                    <?php $date = date('Y-m-d H:i');?>
                                                    <?php if($item['status']==null ): ?>    
                                                            <a href="#" class="btn-sm btn-warning float-center"><i>Waiting</i></a>
                                                    <?php elseif($item['status']=='1' ): ?>    
                                                        <?php if($item['cancel']=='0'): ?>
                                                            <?php if($item['proof_of_deposit']==null) :?>
                                                                <a href="#" class="btn-sm btn-info float-center"><i>Pay deposit!</i></a>
                                                        
                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']==null): ?>
                                                                <?php if($item['deposit_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Deposit Check</i></a>
                                                                <?php elseif($item['deposit_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Deposit Incorrect</i></a>
                                                                <?php elseif($item['deposit_check']==1): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Pay in full!</i></a>
                                                                <?php endif; ?>

                                                            <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']!=null ):  ?>
                                                                <?php if($item['payment_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Payment Check</i></a>
                                                                <?php elseif($item['payment_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Payment Incorrect</i></a>
                                                                <?php elseif($item['payment_check']==1): ?>

                                                                    <?php if($item['review']==null): ?>
                                                                        <?php if($datenow>=$item['check_out']): ?>
                                                                            <a href="#" class="btn-sm btn-dark float-center"><i>Unreviewed</i></a>
                                                                        <?php elseif($datenow<$item['check_out']): ?>
                                                                            <a href="#" class="btn-sm btn-dark float-center"><i>Enjoy trip!</i></a>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <a href="#" class="btn-sm btn-success float-center"><i>Done</i></a>
                                                                    <?php endif; ?>   

                                                                <?php endif; ?>       
                                                            <?php endif; ?>
                                                        <?php elseif($item['cancel']=='1'): ?>
                                                            <?php if($item['account_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel</i></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']==null): ?>
                                                                <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel & refund</i></a>

                                                            <?php elseif($item['account_refund']!=null && $item['proof_refund']!=null): ?>
                                                                <?php if($item['refund_check']==null): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Refund Check</i></a>
                                                                <?php elseif($item['refund_check']==0): ?>
                                                                    <a href="#" class="btn-sm btn-info float-center"><i>Refund Incorrect</i></a>
                                                                <?php elseif($item['refund_check']==1): ?>
                                                                    <a href="#" class="btn-sm btn-danger float-center"><i>Refund Success</i></a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                        <?php endif; ?>

                                                    <?php elseif($item['status']==0): ?>    
                                                        <a href="#" class="btn-sm btn-danger float-center"><i>Rejected</i></a>
                                                    <?php endif; ?>  
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Button Group">
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary mx-1" href="<?=base_url('dashboard/detailreservation/confirm/').$item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i>
                                                        </a>
                                                        <a type="button" class="btn icon btn-outline-success mx-1" title="History" data-bs-toggle="modal" data-bs-target="#historyModal<?=esc($item['id'])?>" data-bs-whatever="@getbootstrap">
                                                            <i class="fa-solid fa-history"></i>
                                                        </a>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Review" class="btn icon btn-outline-info mx-1" href="<?=base_url('dashboard/detailreservation/review/').$item['id']; ?>">
                                                            <i class="fa-solid fa-comments"></i>
                                                        </a>
                                                    </div>
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
                                                                                            <td> Status  </td>
                                                                                            <td>
                                                                                                <?php $date = date('Y-m-d H:i');?>
                                                                                                <?php if($item['status']==null ): ?>    
                                                                                                        <a href="#" class="btn-sm btn-warning float-center"><i>Waiting</i></a>                                                                                                <?php elseif($item['status']=='1' ): ?>    
                                                                                                    <?php if($item['cancel']=='0'): ?>
                                                                                                        <?php if($item['proof_of_deposit']==null) :?>
                                                                                                            <a href="#" class="btn-sm btn-info float-center"><i>Pay deposit!</i></a>
                                                                                                    
                                                                                                        <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']==null): ?>
                                                                                                            <?php if($item['deposit_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Deposit Check</i></a>
                                                                                                            <?php elseif($item['deposit_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Deposit Incorrect</i></a>
                                                                                                            <?php elseif($item['deposit_check']==1): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Pay in full!</i></a>
                                                                                                            <?php endif; ?>

                                                                                                        <?php elseif($item['proof_of_deposit']!=null && $item['proof_of_payment']!=null ):  ?>
                                                                                                            <?php if($item['payment_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Payment Check</i></a>
                                                                                                            <?php elseif($item['payment_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Payment Incorrect</i></a>
                                                                                                            <?php elseif($item['payment_check']==1): ?>

                                                                                                                <?php if($item['review']==null): ?>
                                                                                                                    <?php if($datenow>=$item['check_out']): ?>
                                                                                                                        <a href="#" class="btn-sm btn-dark float-center"><i>Unreviewed</i></a>
                                                                                                                    <?php elseif($datenow<$item['check_out']): ?>
                                                                                                                        <a href="#" class="btn-sm btn-dark float-center"><i>Enjoy trip!</i></a>
                                                                                                                    <?php endif; ?>
                                                                                                                <?php else: ?>
                                                                                                                    <a href="#" class="btn-sm btn-success float-center"><i>Done</i></a>
                                                                                                                <?php endif; ?>   

                                                                                                            <?php endif; ?>       
                                                                                                        <?php endif; ?>
                                                                                                    <?php elseif($item['cancel']=='1'): ?>
                                                                                                        <?php if($item['account_refund']==null): ?>
                                                                                                            <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel</i></a>

                                                                                                        <?php elseif($item['account_refund']!=null && $item['proof_refund']==null): ?>
                                                                                                            <a href="#" class="btn-sm btn-secondary float-center"><i>Cancel & refund</i></a>

                                                                                                        <?php elseif($item['account_refund']!=null && $item['proof_refund']!=null): ?>
                                                                                                            <?php if($item['refund_check']==null): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Refund Check</i></a>
                                                                                                            <?php elseif($item['refund_check']==0): ?>
                                                                                                                <a href="#" class="btn-sm btn-info float-center"><i>Refund Incorrect</i></a>
                                                                                                            <?php elseif($item['refund_check']==1): ?>
                                                                                                                <a href="#" class="btn-sm btn-danger float-center"><i>Refund Success</i></a>
                                                                                                            <?php endif; ?>
                                                                                                        <?php endif; ?>

                                                                                                    <?php endif; ?>

                                                                                                <?php elseif($item['status']==0): ?>    
                                                                                                    <a href="#" class="btn-sm btn-danger float-center"><i>Rejected</i></a>
                                                                                                <?php endif; ?>  
                                                                                            </td>                           
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <?php if($item['status']=='1' || $item['status']=='0'): ?> 
                                                                                                <td><i class="fa fa-level-down" aria-hidden="true"></i> Confirmation Date</td>
                                                                                                <td> : <?= esc(date('l, j F Y H:i:s', strtotime($item['confirmation_date']))); ?> (by admin <?= esc($item['name_admin_refund']); ?>)</td> 
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
                                                                                                        : <?= esc(date('l, j F Y H:i:s', strtotime($item['refund_date']))); ?> (by adm<?= esc($item['admin_refund']); ?>)
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
                                                <?php $i++ ?>
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
<script>
    $(document).ready(function() {
        $('#table-manage').DataTable({
            columnDefs: [{
                targets: ['_all'],
                className: 'dt-head-center'
            }],
            lengthMenu: [5, 10, 20, 50, 100]
        });
    });
</script>
<?= $this->endSection() ?>