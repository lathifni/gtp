<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section"">
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
            <?= $this->include('web/layouts/map-body-2'); ?>
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="row">
            <!-- List Object -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">List Attraction</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive overflow-auto" id="table-user">
                            <script>clearMarker();clearRadius();clearRoute();</script>
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
                                                <script>objectMarker("<?= esc($item['id']); ?>", <?= esc($item['lat']); ?>, <?= esc($item['lng']); ?>);</script>
                                                <td><?= esc($i); ?></td>
                                                <td><?= esc($item['name']); ?></td>
                                                <td>
                                                    <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-primary mx-1" onclick="focusObject(`<?= esc($item['id']); ?>`);">
                                                        <span class="material-symbols-outlined">info</span>
                                                    </a>
                                                </td>
                                                <?php $i++ ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <script>boundToObject();</script>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Section -->
            <!-- <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">Test Info Window Button</h5>
                    </div>
                    <div class="card-body">
                        <a title="Route" class="btn icon btn-outline-primary mx-1" id="routeInfoWindow" onclick="showSteps()"><i class="fa-solid fa-road"></i></a>
                        <a title="Info" class="btn icon btn-outline-primary mx-1" target="_blank" id="infoInfoWindow" href="<?//= base_url('web/object/detail'); ?>"><i class="fa-solid fa-info"></i></a>
                        <a title="Open Nearby" class="btn icon btn-outline-primary mx-1" id="nearbyInfoWindow" onclick="openNearby()"><i class="fa-solid fa-compass"></i></a>
                        <a title="Close Nearby" class="btn icon btn-outline-primary mx-1" onclick="closeNearby()"><i class="fa-solid fa-circle-xmark"></i></a>
                    </div>
                </div>
            </div> -->

            <!-- Nearby section -->
            <?= $this->include('web/layouts/nearby'); ?>
        </div>
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