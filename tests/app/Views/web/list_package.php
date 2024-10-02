<?= $this->extend('web/layouts/main'); ?>

<?= $this->section('content') ?>

<section class="section">
    <div class=" row">
    <div class="col-md-12 col-12">
        <div class="row">
            <!-- List Object -->
            <div class="col-12" id="list-rg-col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title text-center">List Package</h5>
                        <div class="col-auto">
                            <?php if (logged_in()) : ?> <!-- Assuming `logged_in()` is a function that checks if the user is logged in -->
                                <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addcustom'); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary float-right"><i class="fa-solid fa-plus me-3"></i>Custom New Package</button>
                                    <br>
                                </form>
                            <?php else : ?>
                                <button type="button" class="btn btn-primary float-right" onclick="redirectToLogin()"><i class="fa-solid fa-plus me-3"></i>Custom New Package</button>
                                <script>
                                    function redirectToLogin() {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'You are not logged in',
                                            text: 'Please log in to proceed.',
                                            confirmButtonText: 'OK',
                                        }).then(() => {
                                            // Optionally, redirect to the login page
                                            window.location.href = '<?= base_url('/login'); ?>';
                                        });
                                    }
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-12" id="list-rg-col">
                            <?php if (isset($data)) : ?>
                                <?php foreach ($data as $item) : ?>
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                        <div class="col-md-4" style="width: 250px; height: 250px; overflow: hidden;">
                                            <img src="<?= base_url('media/photos/package/' . esc($item['gallery'])); ?>" class="img-fluid rounded-start" alt="Gallery Image" style="object-fit: cover; width: 100%; height: 100%;">
                                        </div>

                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= esc($item['name']); ?></h5>
                                                    <p class="card-text btn-success btn-sm" style="margin: 0; display: inline-block;"><?= esc($item['type_name']); ?></p>
                                                    <p class="card-text" style="margin: 0;">Price    : <?= 'Rp ' . number_format(esc($item['price']), 0, ',', '.'); ?></p>
                                                    <p class="card-text" style="margin: 0;">Capacity : <?= esc($item['min_capacity']); ?> people</p>
                                                    <p class="card-text">
                                                        <?php
                                                            $description = esc($item['description']);
                                                            $maxLength = 150;
                                                            if (strlen($description) > $maxLength) {
                                                                $shortDescription = substr($description, 0, $maxLength);
                                                                echo $shortDescription . '<span class="read-more">... <a href="' . base_url('web/package/') . $item['id'] . '">Read more</a></span>';
                                                            } else {
                                                                echo $description;
                                                            }
                                                        ?>
                                                    </p>
                                                    <div class="d-flex">
                                                        <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="More Info" class="btn icon btn-outline-primary me-2" href="<?= base_url('web/package/') . $item['id']; ?>">
                                                            <i class="fa-solid fa-circle-info"></i> More Info
                                                        </a>

                                                        <?php if (logged_in()) : ?>
                                                            <form class="form form-vertical" id="customForm" action="<?= base_url('/web/detailreservation/addextend'); ?>/<?= esc($item['id']); ?>" method="post" onsubmit="checkRequired(event)" enctype="multipart/form-data">
                                                                <?= csrf_field(); ?>
                                                                <button type="submit" class="btn icon btn-outline-primary" title="Extend Package"><i class="fa-solid fa-plus-square"></i> Extend</button>
                                                                <br>
                                                            </form>
                                                        <?php else : ?>
                                                            <button type="button" class="btn icon btn-outline-primary" title="Extend Package" onclick="redirectToLogin()"><i class="fa-solid fa-plus-square"></i> Extend</button>
                                                            <script>
                                                                function redirectToLogin() {
                                                                    Swal.fire({
                                                                        icon: 'warning',
                                                                        title: 'You are not logged in',
                                                                        text: 'Please log in to proceed.',
                                                                        confirmButtonText: 'OK',
                                                                    }).then(() => {
                                                                        // Optionally, redirect to the login page
                                                                        window.location.href = '<?= base_url('/login'); ?>';
                                                                    });
                                                                }
                                                            </script>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>