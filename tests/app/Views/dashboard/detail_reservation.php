<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

        <!-- Object Detail Information -->
        <div class="col-md-7 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title text-center">Reservation Information</h4>
                        </div>
                        <div class="col-auto">
                            <a href="<?= base_url('dashboard/managereservation/edit'); ?>/<?= esc($data['id']); ?>" class="btn btn-primary float-end"><i class="fa-solid fa-pencil me-3"></i>Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Costumer</td>
                                        <td><?= esc($data['username']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Package Reservation</td>
                                        <td><?= esc($data['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Date Request</td>
                                        <td><?= date('d F Y, h:i:s A', strtotime($data['request_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Check In</td>
                                        <td><?= date('d F Y, h:i:s A', strtotime($data['check_in'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Check Out</td>
                                        <td><?= date('d F Y, h:i:s A', strtotime($data['check_out'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total People</td>
                                        <td><?= esc($data['total_people']); ?> orang</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Price</td>
                                        <td><?= 'Rp ' . number_format(esc($data['total_price']), 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Deposite</td>
                                        <td><?= 'Rp ' . number_format(esc($data['deposit']), 0, ',', '.'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="fw-bold">Facility :</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-12">


        </div>
    </div>
</section>

<?= $this->endSection() ?>