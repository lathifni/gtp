<?= $this->extend('profile/index'); ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Update Profile</h3>
        </div>
        <div class="card-body">
            <form class="form form-vertical" enctype="multipart/form-data" action="<?= base_url('web/profile/save/'.user()->id); ?>" method="post">
                <?= csrf_field();  ?>
                <div class="form-body">
                    <div class="row gx-md-5">
                        <div class="col-md-6 col-12 order-md-first order-last">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="fullname" class="mb-2">Fullname</label>
                                        <input type="text" id="fullname" class="form-control"
                                               name="fullname" placeholder="Fullname" value="<?= user()->fullname; ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="username" class="mb-2">Username</label>
                                        <input type="text" id="username" class="form-control"
                                        name="username" placeholder="Username" disabled value="<?= user()->username; ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="email" class="mb-2">Email</label>
                                        <input type="email" id="email" class="form-control"
                                               name="email" placeholder="Email" disabled value="<?= user()->email; ?>">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="address" class="mb-2">Address</label>
                                        <textarea id="address" class="form-control"
                                               name="address" placeholder="Address" value="<?= user()->address; ?>"><?= user()->address; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="phone" class="mb-2">Phone</label>
                                        <input type="text" id="phone" class="form-control"
                                               name="phone" placeholder="Phone" value="<?= user()->phone; ?>">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end mb-3">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset"
                                            class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 order-md-last order-first">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="user_image" class="mb-2">Profile Picture</label>
                                        <div class="text-md-start text-center mb-3" id="avatar-container">
                                            <img src="<?= base_url('media/photos/user/'); ?><?= user()->user_image; ?>" alt="user_image" class="img-fluid img-thumbnail rounded-circle" id="avatar-preview">
                                        </div>
                                        <input class="form-control" type="file" id="user_image" name="user_image"   accept="image/png, image/jpeg, image/gif">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
