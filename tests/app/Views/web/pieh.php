<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <!--map-->
    <div class="col-md-7 col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-auto">
                        <h5 class="card-title">Google Maps with Location</h5>
                    </div>
                    <?= $this->include('web/layouts/map-head'); ?>
                </div>
            </div>
            <?= $this->include('web/layouts/map-body-7'); ?>
        </div>
    </div>

    <div class="col-md-5 col-12">
        <div class="row">
            <!--Home-->
            <div class="col-12" id="list-at-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Pieh Island</h5>
                    </div>
                    <div class="card-body">
                        <script>
                            clearMarker();
                            clearRadius();
                            clearRoute();
                        </script>

                        <?php foreach ($data as $item) : ?>
                            <script>
                                objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);
                            </script>
                            <div class="row">
                                <div class="col table-responsive">
                                    <div>
                                        <?php print $item['description']; ?>
                                    </div>
                                    <div>
                                        <tr>
                                            <td class="fw-bold">Price</td>
                                            <td> : <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?></td>
                                        </tr>
                                    </div>
                                    <div>
                                        <tr>
                                            <td class="fw-bold">Payment Category </td>
                                            <td>
                                                <?php if ($item['category'] == 0): ?>
                                                    : Group
                                                <?php elseif ($item['category'] == 1): ?>
                                                    : Individu
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($data2 as $item2) : ?>
                            <script>
                                objectMarker("<?= esc($item2['id']); ?>", <?= esc($item2['lat']); ?>, <?= esc($item2['lng']); ?>);
                            </script>
                        <?php endforeach; ?>

                        <!-- Object Media -->
                        <?= $this->include('web/layouts/our_gallery_video'); ?>

                        <div class="d-grid gap-2 pt-2">
                            <button type="button" class="btn btn-outline-primary" onclick="focusObject(`<?= esc($item['id']); ?>`);">
                                <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">info</span> More Info
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Nearby section -->
        <?= $this->include('web/layouts/track'); ?>
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