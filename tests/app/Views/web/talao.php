<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
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
                <?= $this->include('web/layouts/map-body-3'); ?>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="row">
                <!--Home-->
                <div class="col-12" id="list-at-col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Water Attractions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive overflow-auto" id="table-user">
                                <script>
                                    clearMarker();
                                    clearRadius();
                                    clearRoute();
                                </script>
                                <table class="table table-hover mb-0 table-lg">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-data">
                                        <?php if (isset($data)) : ?>
                                            <?php $i = 1; ?>
                                            <?php foreach ($data as $item) : ?>
                                                <tr>
                                                    <script>
                                                        objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);
                                                    </script>
                                                    <td><?= esc($i); ?></td>
                                                    <td class="fw-bold"><?= esc($item['name']); ?></td>
                                                    <td>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-primary mx-1" onclick="focusObject(`<?= esc($item['id']); ?>`);">
                                                            <span class="material-symbols-outlined">info</span>
                                                        </a>
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

                            <!-- Object Media -->
                            <?= $this->include('web/layouts/our_gallery_video'); ?>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Nearby section -->
            <?= $this->include('web/layouts/nearby'); ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    const myModal = document.getElementById('videoModal');
    const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    myModal.addEventListener('shown.bs.modal', () => {
        console.log(videoSrc);
        document.getElementById('video').setAttribute('src', videoSrc);
    });
    myModal.addEventListener('hide.bs.modal', () => {
        document.getElementById('video').setAttribute('src', '');
    });

    $('#direction-row').hide();
    $('#check-track-col').hide();
    $('#check-nearby-col').hide();
    $('#result-track-col').hide();
    $('#result-nearby-col').hide();
</script>
<?= $this->endSection() ?>