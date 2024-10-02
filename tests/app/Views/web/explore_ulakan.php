<?= $this->extend('web/layouts/main'); ?>

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
                <?= $this->include('web/layouts/map-body-4'); ?>
            </div>
        </div>


        <div class="col-md-4 col-12">
            <div class="row">
                <!--popular-->
                <div class="col-12" id="list-object-col">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title text-center">Explore Ulakan</h5>
                            <!-- <hr class="hr" /> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive overflow-auto" id="table-user">
                                <!-- <script>
                                    clearMarker();
                                    clearRadius();
                                    clearRoute();
                                </script> -->
                                <table class="table table-hover mb-0 table-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-data">
                                        <tr>
                                            <td>
                                                <i class="fa-solid fa-utensils text-danger"></i> Culinary Place
                                            </td>
                                            <td>
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show All" class="btn icon btn-primary mx-1" onclick="showMap('cp');">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa-solid fa-bed text-info"></i> Homestay
                                            </td>
                                            <td>
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show All" class="btn icon btn-primary mx-1" onclick="showMap('ho');">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa-solid fa-cart-shopping text-warning"></i> Souvenir Place
                                            </td>
                                            <td>
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show All" class="btn icon btn-primary mx-1" onclick="showMap('sp');">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="fa-solid fa-mosque text-success"></i> Worship Place
                                            </td>
                                            <td>
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show All" class="btn icon btn-primary mx-1" onclick="showMap('wp');">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a title="Around You" class="btn icon btn-outline-primary mx-1" onclick="openExplore()">
                                    <i class="fa-solid fa-compass me-3"></i>Search object around you?
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Nearby section -->
                <?= $this->include('web/layouts/explore'); ?>
            </div>
        </div>
        <!-- Direction section -->
        <?= $this->include('web/layouts/direction'); ?>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $('#direction-row').hide();
    $('#check-explore-col').hide();
    $('#result-explore-col').hide();
</script>
<?= $this->endSection() ?>