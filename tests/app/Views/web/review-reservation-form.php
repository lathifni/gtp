<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
$addhome = in_array('addhome', $uri);
?>

<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
        transform: scaleX(-1);
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
    }

    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label {
        color: orange;
    }

    .rating2 {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating2 {
        color: orange;
    }
</style>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>
        
        <!-- Object Detail Information -->
        <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Review Package</h4>
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
                                            <td class="fw-bold">Price</td>
                                            <td><?= 'Rp ' . number_format(esc($data_package['price']), 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Description</td>
                                        </tr>
                                    </tbody>  
                                </table>
                                <p><?= esc($data_package['description']); ?></p>
                            </div>
                        </div>

                        <div class="row">
                        <?php if($detail['status']==null): ?>
                            <p class="btn-secondary"><i>User can't add a review because this reservation not accepted yet</i></p>
                        <?php elseif($detail['status']=='0'): ?>
                            <p class="btn-danger"><i>User can't add a review because this reservation rejected</i></p>
                        <?php elseif($detail['status']==1): ?>
                            <?php $date= date('Y-m-d H:i');
                                if($check_out<$date):
                            ?>
                                <?php if($detail['review']==null): ?>
                                    <?php if(user()->id == $detail['user_id']): ?>
                                        <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/savereview/').$detail['id']; ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                            <?= csrf_field();  ?>                                
                                            <label for="rating">Rating:</label>
                                            <div class="rating">
                                                <input type="radio" id="star5" name="rating" value="5">
                                                <label for="star5"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star4" name="rating" value="4">
                                                <label for="star4"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star3" name="rating" value="3">
                                                <label for="star3"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star2" name="rating" value="2">
                                                <label for="star2"><i class="fas fa-star"></i></label>
                                                <input type="radio" id="star1" name="rating" value="1" required>
                                                <label for="star1"><i class="fas fa-star"></i></label>
                                            </div><br>
                                            
                                            <label for="review">Review:</label><br>
                                            <textarea id="review" name="review" class="form-control" rows="4" cols="50" required></textarea><br><br>
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </form>
                                    <?php else : ?>
                                        <i class="btn-warning btn-sm">The user hasn't given a review yet</i>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                <?php else: ?>
                                    <div class="col table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">Rating</td>
                                                    <td>
                                                        <div class="rating2">
                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                            <?php if ($i <= $detail['rating']) : ?>
                                                                <i name="rating2" class="fas fa-star"></i>
                                                            <?php else: ?>
                                                                <i name="rating2" class="far fa-star"></i>
                                                            <?php endif; ?>
                                                        <?php endfor; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Review</td>
                                                    <td><?= esc($detail['review']); ?></td>
                                                </tr>
                                            </tbody>  
                                        </table>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="btn-warning"><i>User can add a review if this reservation is complete</i></p>
                            <?php endif; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
        </div>

            <div class="col-md-6 col-12" >
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Review Homestay</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach($booking as $db):?>

                                <div class="row">
                                    <div class="col table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold">Homestay Name</td>
                                                    <td><?= esc($db['name']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Unit Homestay</td>
                                                    <td><?= esc($db['name_type']); ?> <?= esc($db['unit_number']); ?> <?= esc($db['nama_unit']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Price</td>
                                                    <td><?= 'Rp ' . number_format(esc($db['price']), 0, ',', '.'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Address</td>
                                                </tr>
                                                <tr>
                                                    <td><?= esc($db['address']); ?></td>
                                                </tr>
                                            </tbody>  
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                <?php if($db['status']==null): ?>
                                    <p class="btn-secondary"><i>User can't add a review because this reservation not accepted yet</i></p>
                                <?php elseif($db['status']=='0'): ?> 
                                    <p class="btn-danger"><i>User can't add a review because this reservation rejected</i></p>
                                <?php elseif($db['status']==1): ?>
                                    <?php $date= date('Y-m-d H:i');
                                        if($check_out<$date):
                                    ?>
                                        <?php if($db['review']==null): ?>
                                            <?php if(user()->id == $detail['user_id']): ?>
                                                <form class="form form-vertical" id="reviewForm" action="<?= base_url('/web/detailreservation/savereviewunit/').$db['reservation_id']; ?>" method="post" enctype="multipart/form-data">
                                                    <?= csrf_field();  ?>   
                                                    <input type="hidden" name="reservation_id" value="<?= $db['reservation_id'] ?>">                             
                                                    <input type="hidden" name="unit_number" value="<?= $db['unit_number'] ?>">                             
                                                    <input type="hidden" name="homestay_id" value="<?= $db['homestay_id'] ?>">                             
                                                    <input type="hidden" name="unit_type" value="<?= $db['unit_type'] ?>">                             
                                                    <label for="rating">Rating:</label>
                                                    <div class="rating">
                                                        <input type="radio" id="star5" name="rating" value="5">
                                                        <label for="star5"><i class="fas fa-star"></i></label>
                                                        <input type="radio" id="star4" name="rating" value="4">
                                                        <label for="star4"><i class="fas fa-star"></i></label>
                                                        <input type="radio" id="star3" name="rating" value="3">
                                                        <label for="star3"><i class="fas fa-star"></i></label>
                                                        <input type="radio" id="star2" name="rating" value="2">
                                                        <label for="star2"><i class="fas fa-star"></i></label>
                                                        <input type="radio" id="star1" name="rating" value="1" required>
                                                        <label for="star1"><i class="fas fa-star"></i></label>
                                                    </div><br>
                                                    
                                                    <label for="review">Review:</label><br>
                                                    <textarea id="review" name="review" class="form-control" rows="4" cols="50" required></textarea><br><br>
                                                    <button type="submit" class="btn btn-primary">Send</button>
                                                </form>
                                            <?php else : ?>
                                                <i class="btn-warning btn-sm">The user hasn't given a review yet</i>
                                            <?php endif; ?>
                                    </div>
                                            
                                    <div class="row">
                                        <?php else: ?>
                                            <div class="col table-responsive">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Rating</td>
                                                            <td>
                                                                <div class="rating2">
                                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                    <?php if ($i <= $db['rating']) : ?>
                                                                        <i name="rating2" class="fas fa-star"></i>
                                                                    <?php else: ?>
                                                                        <i name="rating2" class="far fa-star"></i>
                                                                    <?php endif; ?>
                                                                <?php endfor; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Review</td>
                                                            <td><?= esc($db['review']); ?></td>
                                                        </tr>
                                                    </tbody>  
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="btn-warning"><i>User can add a review if this reservation is complete</i></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>                              
                            <hr>
                        <?php endforeach; ?>
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