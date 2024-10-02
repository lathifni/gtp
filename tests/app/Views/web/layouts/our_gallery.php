<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Our Gallery</h5>
                </div>
                <div class="card-body">
                    <div class="row gallery" data-bs-toggle="modal" data-bs-target="#galleryModal">
                        <?php $i = 0; ?>
                        <?php foreach ($data['gallery'] as $g) : ?>
                            <div class="col-6 col-sm-6 col-lg-3 mt-2 mt-md-2 mb-md-2 mb-2">
                                <a href="#">
                                    <img class="w-100 active" src="<?= base_url('media/photos/' . $folder . '/' . esc($g));
                                                                    ?>" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i); ?>" />
                                </a>
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalTitle">
                    Our Gallery
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="Gallerycarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php $i = 0; ?>
                        <?php foreach ($data['gallery'] as $x) : ?>
                            <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php $i = 0; ?>
                        <?php foreach ($data['gallery'] as $g) : ?>
                            <div class="carousel-item<?= ($i == 0) ? ' active' : ''; ?>">
                                <img class="d-block w-100" src="<?= base_url('media/photos/' . $folder . '/' . esc($g)); ?>">
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
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