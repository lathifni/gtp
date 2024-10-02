<?= $this->extend('maps/main'); ?>

<?= $this->section('content') ?>
<style>
    .rating {
        display: inline-block;
        font-size: 25px;
    }
    
    .rating {
        color: orange;
    }
</style>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Homestay Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Name</td>
                                        <td><?= esc($data['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Address</td>
                                        <td><?= esc($data['address']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Contact Person</td>
                                        <td><?= esc($data['contact_person']); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Description</p>
                            <p><?= esc($data['description']);
                                ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Facility</p>
                            <p>
                                <?php if (isset($facilityhome)) : ?>                      
                                    <?php foreach ($facilityhome as $dt_fc) : ?>
                                        <li>
                                            <?= esc($dt_fc['name']); ?> (<?= esc($dt_fc['description']); ?>)
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <div class="card-header">
                    <h4 class="card-title text-center">Homestay Unit</h4>
                </div>
                
                <?php if (isset($unit)) : ?>                      
                    <div class="row sm-8">
                        <?php foreach ($unit as $item) : ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"> <?= esc($item['name_type']); ?> <?= esc($item['nama_unit']); ?> <?= esc($item['unit_number']); ?></h5>
                                        <div class="rating text-center ">
                                                <?php foreach($rating as $date): ?>
                                                    <?php foreach($date as $dt => $rate): ?>
                                                        <?php if($rate['rating']!=null): ?>
                                                            <?php if($rate['unit_number']==$item['unit_number'] && $rate['homestay_id']==$item['homestay_id'] && $rate['unit_type']==$item['unit_type']): ?>
                                                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                    <?php if ($i <= $rate['rating']) : ?>
                                                                        <i name="rating" class="fas fa-star"></i>
                                                                    <?php else: ?>
                                                                        <i name="rating" class="far fa-star"></i>
                                                                    <?php endif; ?>
                                                                <?php endfor; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                        </div>
                                        <p class="card-text">
                                            Price : <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?> <br>
                                            Capacity : <?= esc($item['capacity']); ?> orang
                                        </p>

                                        <p class="card-text"><?= esc($item['description']); ?></p>
                                        <p class="card-text">Facility :
                                            <?php if (isset($facility)) : ?>                      
                                                <?php foreach ($facility as $dt_fc) : ?>
                                                    <?php foreach ($dt_fc as $dt) : ?>
                                                        <?php if($dt['unit_number']==$item['unit_number'] && $dt['homestay_id']==$item['homestay_id'] && $dt['unit_type']==$item['unit_type']): ?>
                                                        <li>
                                                             <?= esc($dt['name']); ?> (<?= esc($dt['description']); ?>)
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </p>

                                        <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#exampleModal<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" data-bs-whatever="@getbootstrap"><i class="fa fa-photo"></i></button>
                                        <div class="modal fade" id="exampleModal<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Gallery Unit</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                        <div id="GalleryUnitcarousel<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                                            <div class="carousel-indicators">
                                                                <?php $i = 0; ?>
                                                                <?php foreach ($gallery_unit as $dt => $x) : ?>
                                                                    <button type="button" data-bs-target="#GalleryUnitcarousel<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" data-bs-slide-to="<?= esc($i); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                                                                    <?php $i++; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <div class="carousel-inner">
                                                                <?php $i = 0; ?>
                                                                <?php foreach ($gallery_unit as $g) : ?>
                                                                    <?php if($g['unit_number']==$item['unit_number']  &&  $g['unit_type']==$item['unit_type']): ?>
                                                                        <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                                                            <img class="d-block w-100" src="<?= base_url('media/photos/unithomestay/'.esc($g['url']))?>">
                                                                        </div>
                                                                        <?php $i++; ?>
                                                                        <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <a class="carousel-control-prev" href="#GalleryUnitcarousel<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" role="button" type="button" data-bs-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#GalleryUnitcarousel<?=esc($item['unit_number'])?><?=esc($item['unit_type'])?>" role="button" data-bs-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            </a>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="d-inline-flex gap-1">
                                            <button class="btn btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= esc($item['unit_number']);?><?= esc($item['unit_type']);?>" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="fa fa-comments"></i>
                                            </button>
                                        </p>
                                        <?php foreach ($review as $dt) : ?>
                                            <?php foreach ($dt as $value => $d) : ?>
                                                    <?php if($d['unit_number']==$item['unit_number'] && $d['homestay_id']==$item['homestay_id'] && $d['unit_type']==$item['unit_type']): ?>
                                                        <div class="collapse" id="collapse<?= esc($d['unit_number']);?><?= esc($d['unit_type']);?>">
                                                            <div class="card card-body">
                                                                <strong>@<?= esc($d['username']) ?></strong>
                                                                <div class="rating2 ">Rating  :
                                                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                            <?php if ($i <= $d['rating']) : ?>
                                                                                <i name="rating2" class="fas fa-star"></i>
                                                                            <?php else: ?>
                                                                                <i name="rating2" class="far fa-star"></i>
                                                                            <?php endif; ?>
                                                                        <?php endfor; ?>
                                                                </div>
                                                                <div>Review  : <?= esc($d['review']) ?></div>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-5 col-12">
            <!-- Object Media -->
            <?= $this->include('web/layouts/our_gallery'); ?>

        </div>
    </div>
</section>

<?= $this->endSection() ?>