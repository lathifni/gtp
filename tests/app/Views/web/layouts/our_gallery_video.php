<div class="d-grid gap-2">
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#galleryModal" <?= (count($data[0]['gallery']) == 0) ? 'disabled' : ''; ?>>
        <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">image</span> Open Gallery
    </button>
    <button type="button" id="video-play" class="btn-play btn btn-outline-primary" data-bs-toggle="modal" data-src="<?= base_url('media/videos/' . esc($data[0]['video_url']) . ''); ?>" data-bs-target="#videoModal" <?= ($data[0]['video_url'] == '') ? 'disabled' : ''; ?>>
        <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">play_circle</span> Play Video
    </button>

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
                            <?php foreach ($data[0]['gallery'] as $x) : ?>
                                <button type="button" data-bs-target="#Gallerycarousel" data-bs-slide-to="<?= esc($i); ?>" class="<?= ($i == 0) ? 'active' : ''; ?>"></button>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php $i = 0; ?>
                            <?php foreach ($data[0]['gallery'] as $g) : ?>
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

    <div class="modal fade text-left" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel17">Video</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <video src="" class="embed-responsive-item" id="video" controls>Sorry, your browser doesn't support embedded videos</video>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>