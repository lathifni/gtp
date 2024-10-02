<?= $this->extend('web/layouts2/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <!--map-->
        <div class="col-md-8 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-auto">
                            <h5 class="card-title">Google Maps with Location</h5>
                        </div>
                        <?= $this->include('web/layouts/map-head'); ?>
                    </div>
                </div>
                <?= $this->include('web/layouts/map-body'); ?>
            </div>
        </div>


        <div class="col-md-4 col-12">
            <div class="row">
                <!--Home-->
                <div class="col-12" id="list-rec-col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">GTP Ulakan</h5>
                        </div>
                        <div class="card-body">
                            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"></li>
                                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" class=""></li>
                                    <li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" class=""></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <a>
                                            <img src="<?= base_url('media/photos'); ?>/1.jpg" class="d-block w-100" alt="" onclick="">
                                        </a>
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Gerbang Masuk</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <a>
                                            <img src="<?= base_url('media/photos'); ?>/2.jpg" class="d-block w-100" alt="" onclick="">
                                        </a>
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Tampak Depan 1</h5>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <a>
                                            <img src="<?= base_url('media/photos'); ?>/3.jpg" class="d-block w-100" alt="" onclick="">
                                        </a>
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Tampak Depan 2</h5>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                            <br>
                            <div>
                                <?php if (isset($data)) : ?>
                                    <?php foreach ($data as $item) : ?>
                                        <div class="row">
                                            <div class="col table-responsive">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold">Name</td>
                                                            <td><?= esc($item['name']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Type of Tourism</td>
                                                            <td><?= esc($item['type_of_tourism']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Address</td>
                                                            <td><?= esc($item['address']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Open</td>
                                                            <td><?= esc($item['open']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Close</td>
                                                            <td><?= esc($item['close']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Ticket Price</td>
                                                            <td><?= 'Rp ' . number_format(esc($item['ticket_price']), 0, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Contact Person</td>
                                                            <td><?= esc($item['contact_person']); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nearby section -->
            <?= $this->include('web/layouts/nearby'); ?>
        </div>
    </div>
    <!-- Direction section -->
    <?= $this->include('web/layouts/direction'); ?>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $('#direction-row').hide();
    $('#check-nearby-col').hide();
    $('#result-nearby-col').hide();
</script>
<?= $this->endSection() ?>