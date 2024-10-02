<?php
$uri = service('uri')->getSegments();
$edit = in_array('edit', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('styles') ?>
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/form-element-select.css'); ?>">

<style>
    .filepond--root {
        width: 100%;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="row">
        <script>
            currentUrl = '<?= current_url(); ?>';
        </script>

            <!-- ADD DATA Service -->
                <div class="modal fade" id="servicesPackageModal" tabindex="-1" aria-labelledby="servicesPackageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="servicesPackageModalLabel">Data Services</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="row g-3" action="<?= base_url('dashboard/servicepackage/create'); ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card-header">
                                    <?php @csrf_field(); ?>
                                    <div class="row g-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Service Name</label>
                                                <input type="text" class="form-control" id="name" name="name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                    <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                    <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            <!-- end menambahkan data Service -->


            <!-- Menambahkan Service -->
                            <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Activity</button> -->
                            <div class="modal fade" id="detailServicesPackageModal" tabindex="-1" aria-labelledby="detailServicesPackageModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="detailServicesPackageModalLabel">Service Package</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form class="row g-3" action="<?= base_url('dashboard/servicepackage/createservicepackage/').$id; ?>" method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="card-header">
                                                <?php @csrf_field(); ?>
                                                <div class="row g-4">
                                                    <div class="col-md-12">
                                                        <label for="id_service">Service </label>
                                                        <select class="form-select" name="id_service" required>
                                                                <option value="" selected>Select the service</option>
                                                                <?php foreach ($servicelist as $item):?>
                                                                    <option value="<?= esc($item['id']); ?>">
                                                                        <?= esc($item['name']); ?> - <?= 'Rp' . number_format(esc($item['price']), 0, ',', '.'); ?>
                                                                        <?php if ($item['category'] == 0): ?>
                                                                            - Group
                                                                        <?php elseif ($item['category'] == 1): ?>
                                                                            - Individu
                                                                        <?php endif; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div><br>
                                                <div class="row g-4">
                                                    <div class="col-md-12">
                                                    <label>
                                                        <input required type="radio" name="status_service" value="1">
                                                        Service
                                                    </label>
                                                    <label>
                                                        <input required type="radio" name="status_service" value="0">
                                                        Non-service
                                                    </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal-footer">
                                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                            <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                            <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                        </div>
                                        </div>
                                    </form>
                                    
                                    </div>
                                </div>
                            </div>
            <!-- end Menambahkan Service -->

        <!-- Object Detail Information -->
        <div class="col-md-5 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center"><?= $title; ?></h4>
                </div>
                <div class="card-body">
                    <form id="packageForm" class="form form-vertical" action="<?= ($data['custom'] == 1) ? base_url('dashboard/package/updatecustom/').$data['id'] : (($edit) ? base_url('dashboard/package/update/') . $data['id'] : base_url('dashboard/package')); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="form-group mb-4">
                                <label for="name" class="mb-2">Package Name</label>
                                <input type="text" id="name" class="form-control" name="name" placeholder="Package Name" value="<?= ($edit) ? $data['name'] : old('name'); ?>" required autocomplete="off">
                            </div>
                            <fieldset class="form-group mb-4">
                                <label for="type" class="mb-2">Package Type</label>
                                <select class="form-select" id="type" name="type">
                                    <?php foreach ($type as $t) : ?>
                                        <?php if ($edit) : ?>
                                            <option value="<?= esc($t['id']); ?>" <?= (esc($data['type_id']) == esc($t['id'])) ? 'selected' : ''; ?>><?= esc($t['type_name']); ?></option>
                                        <?php else : ?>
                                            <option value="<?= esc($t['id']); ?>"><?= esc($t['type_name']); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </fieldset>
                            <div class="form-group mb-4">
                                <label for="price" class="mb-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp </span>
                                    <input readonly type="number" id="price" class="form-control" name="price" placeholder="The price based on the activities and services added" aria-label="Price" aria-describedby="price" value="<?= ($edit) ? $totalPrice : old('price'); ?>">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="min_capacity" class="mb-2">Minimal Capacity</label>
                                <input type="number" min="1" id="min_capacity" class="form-control" name="min_capacity" placeholder="Minimal Capacity" value="<?= ($edit) ? $data['min_capacity'] : old('min_capacity'); ?>" autocomplete="off" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="contact_person" class="mb-2">Contact Person</label>
                                <input type="tel" id="contact_person" class="form-control" name="contact_person" placeholder="Contact Person" value="<?= ($edit) ? $data['contact_person'] : old('contact_person'); ?>" autocomplete="off">
                            </div>
                            <div class="form-group mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?= ($edit) ? $data['description'] : old('description'); ?></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label for="gallery" class="form-label">Gallery</label>
                                <input class="form-control" accept="image/*" type="file" name="gallery[]" id="gallery" multiple>
                            </div>
                            <div class="form-group mb-4">
                                <label for="video" class="form-label">Video</label>
                                <input class="form-control" accept="video/*, .mkv" type="file" name="video" id="video">
                            </div>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            <?php if (($edit)) : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save Change</button>
                            <?php else : ?>
                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <br/>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-12">
            <!-- Services -->
            <?php if(($edit)) : ?>
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="form-group mb-4">
                                    <div class="col-auto ">
                                        <div class="btn-group float-right" role="group">
                                            <!-- <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#servicesPackageModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> New Services</button> -->
                                            <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#detailServicesPackageModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> <b>Add Services Package</b></button>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if(session()->has('success')) : ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: '<?= session('success') ?>',
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <?php if(session()->has('failed')) : ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Failed!',
                                                text: '<?= session('failed') ?>',
                                            });
                                        </script>
                                    <?php endif; ?>

                                    <label for="facility" class="mb-2">Services</label>
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Category</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($detailservice)) : ?>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($detailservice as $item => $value) : ?>
                                                            <?php if ($value['status']=="1") : ?>
                                                                <tr>
                                                                    <td><?= esc($i++); ?></td>
                                                                    <td><?= esc($value['name']); ?></td>
                                                                    <td><?= 'Rp' . number_format(esc($value['price']), 0, ',', '.'); ?> </td>
                                                                    <td>
                                                                        <?php if ($value['category'] == 0): ?>
                                                                            Group
                                                                        <?php elseif ($value['category'] == 1): ?>
                                                                            Individu
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <form action="<?= base_url('dashboard/servicepackage/delete/').$value['package_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                                <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                                <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </td> 
                                                                </tr> 
                                                            <?php endif; ?>       
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
        
        
                                    <label for="facility" class="mb-2">Non-Services</label>
                                    <div class="table-responsive">
                                        <div class="table-wrapper">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($detailservice)) : ?>
                                                        <?php $i = 1; ?>
                                                        <?php foreach ($detailservice as $item => $value) : ?>
                                                            <?php if ($value['status']=="0") : ?>
                                                                <tr>
                                                                    <td><?= esc($i++); ?></td>
                                                                    <td><?= esc($value['name']); ?></td>
                                                                    <td>
                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <form action="<?= base_url('dashboard/servicepackage/delete/').$value['package_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                <input type="hidden" name="service_package_id" value="<?= esc($value['service_package_id']); ?>">
                                                                                <input type="hidden" name="name" value="<?= esc($value['name']); ?>">
                                                                                <input type="hidden" name="status" value="<?= esc($value['status']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-times"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </td> 
                                                                </tr> 
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Activity</h4>
                        </div>
                        <?php if(session()->has('success')) : ?>
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: '<?= session('success') ?>',
                                });
                            </script>
                        <?php endif; ?>

                        <?php if(session()->has('failed')) : ?>
                            <script>
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Failed!',
                                    text: '<?= session('failed') ?>',
                                });
                            </script>
                        <?php endif; ?>

                        <div class="card-body">
                    <!-- Menambahkan hari paket -->
                        
                        <div class="col-auto ">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#dayModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> <b>Day</b></button>
                                <button type="button" class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#activityModal" data-bs-whatever="@getbootstrap"><i class="fa fa-plus"></i> <b>Activity</b></button>
                            </div>
                        </div>
                        <div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="dayModalLabel">Package Day</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= base_url('dashboard/packageday/createday') . '/' . $data['id']; ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <h5 class="card-title"><?= esc($data['name']) ?></h5>
                                            <div class="row g-4">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="package">Package</label>
                                                        <input type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="day">Day</label>
                                                        <input type="number" min="1" class="form-control" id="day" name="day" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description" name="description" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editdayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="dayModalLabel">Edit Package Day</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="row g-3" action="<?= base_url('dashboard/packageday/createday') . '/' . $data['id']; ?>" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="card-header">
                                            <?php @csrf_field(); ?>
                                            <h5 class="card-title"><?= esc($data['name']) ?></h5>
                                            <div class="row g-4">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="package">Package</label>
                                                        <input type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="day">Day</label>
                                                        <input type="number" min="1" class="form-control" id="day" name="day" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" id="description" name="description" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                        <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    <!-- end menambahkan hari paket -->
                    
                    <!-- Menambahkan Aktivitas -->
                        <div class="col-sm-2 float-end">
                                                <!-- <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Activity</button> -->
                                                <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="activityModalLabel">Activity Package Day </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form class="row g-3" action="<?= base_url('dashboard/packageday/createactivity') . '/' . $data['id']; ?>" method="post" >
                                                            <div class="modal-body">
                                                                <div class="card-header">
                                                                    <?php @csrf_field(); ?>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-12">
                                                                            <input hidden type="text" class="form-control" id="package" name="package" placeholder="Pxxxxx" disabled value="<?= esc($data['id']) ?>">
                                                                            <label for="day">Activity Day</label>
                                                                            <select class="form-select" name="day" required>
                                                                                    <option value="" selected>Select the day</option>
                                                                                <?php foreach ($day as $item => $keyy) : ?>
                                                                                    <option value="<?= esc($keyy['day']); ?>">Activity Day <?= esc($keyy['day']); ?></option>                                                                
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div><br>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-3">
                                                                            <label for="activity">Activity</label>
                                                                            <input type="number" min='1' required class="form-control" id="activity" name="activity">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label for="activity_type">Activity Type</label>
                                                                            <select class="form-control" name="activity_type" id="activity_type" required>
                                                                                <option value="" selected>Select Type</option>
                                                                                <option value="CP">Culinary</option>
                                                                                <option value="WO">Worship</option>
                                                                                <option value="SP">Souvenir Place</option>
                                                                                <option value="HO">Homestay</option>
                                                                                <option value="FC">Facility</option>
                                                                                <option value="A">Attraction</option>
                                                                                <option value="EV">Event</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label for="object">Object</label>
                                                                            <select class="form-control" name="object" id="object" required>
                                                                                <option disabled selected>Select Object</option>
                                                                                <?php foreach ($object['culinary'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">[Culinary] <?= esc($item['name']); ?> - Rp0 -Shopping not include</option>                                                                
                                                                                <?php endforeach; ?>
                                                                                <?php foreach ($object['worship'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">[Worship] <?= esc($item['name']); ?> - Rp0 -Shopping not include</option>                                                                
                                                                                <?php endforeach; ?>
                                                                                <?php foreach ($object['souvenir'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">[Souvenir] <?= esc($item['name']); ?> - Rp0 -Shopping not include</option>                                                                
                                                                                <?php endforeach; ?>
                                                                                <?php foreach ($object['facility'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">
                                                                                        [Facility] <?= esc($item['name']); ?> - Rp<?= esc($item['price']); ?>
                                                                                        <?php if ($item['category'] == 0): ?>
                                                                                            - Group
                                                                                        <?php elseif ($item['category'] == 1): ?>
                                                                                            - Individu
                                                                                        <?php endif; ?>
                                                                                    </option>                                                                
                                                                                <?php endforeach; ?>
                                                                                <?php foreach ($object['attraction'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">
                                                                                        [Attraction] <?= esc($item['name']); ?> - Rp<?= esc($item['price']); ?> 
                                                                                        <?php if ($item['category'] == 0): ?>
                                                                                            - Group
                                                                                        <?php elseif ($item['category'] == 1): ?>
                                                                                            - Individu
                                                                                        <?php endif; ?>
                                                                                    </option>                                                                
                                                                                <?php endforeach; ?>
                                                                                <?php foreach ($object['event'] as $item) : ?>
                                                                                    <option value="<?= esc($item['id']); ?>">
                                                                                        [Event] <?= esc($item['name']); ?> - Rp<?= esc($item['price']); ?>
                                                                                        <?php if ($item['category'] == 0): ?>
                                                                                            - Group
                                                                                        <?php elseif ($item['category'] == 1): ?>
                                                                                            - Individu
                                                                                        <?php endif; ?>
                                                                                    </option>                                                                
                                                                                <?php endforeach; ?>
                                                                            </select>                                                              
                                                                        </div>
                                                                    </div><br>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-12">
                                                                            <label for="description_activity">Description</label>
                                                                            <input type="text" class="form-control" id="description_activity" name="description_activity" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                                                <button type="submit" class="btn btn-outline-primary me-1 mb-1"><i class="fa-solid fa-add"></i></button>
                                                                <button type="reset" class="btn btn-outline-danger me-1 mb-1"><i class="fa-solid fa-trash-can"></i> </button>
                                                            </div>
                                                        </form>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                    <!-- end Menambahkan Aktivitas -->

                            <?php if (session()->getFlashdata('pesan')) : ?>
                                <div class="alert alert-success col-sm-10 mx-auto" role="alert">
                                    <?= session()->getFlashdata('pesan'); ?>
                                </div>
                            <?php endif;  ?>
                            
                            <?php if (isset($day)) : ?>
                                <?php foreach ($day as $item => $key) : ?>
                                    <div class="table-responsive">
                                        <div class="table-wrapper">

                                            <div class="table-title">
                                                <br>
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <b>Day <?= esc($key['day']); ?></b>
                                                        <p><?= esc($key['description']); ?></p>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <div class="btn-group float-end" role="group" aria-label="Basic example">                                                    
                                                            <form action="<?= base_url('dashboard/packageday/deleteday/').$key['package_id']; ?>" method="post" class="d-inline">
                                                                <?= csrf_field(); ?>
                                                                <input type="hidden" name="package_id" value="<?= esc($key['package_id']); ?>">
                                                                <input type="hidden" name="day" value="<?= esc($key['day']); ?>">
                                                                <input type="hidden" name="description" value="<?= esc($key['description']); ?>">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this day?');"><i class="fa fa-trash"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">No</th>
                                                        <!-- <th style="width: 10%;">Activity Type</th> -->
                                                        <th style="width: 20%;">Object</th>
                                                        <th style="width: 25%;">Description</th>
                                                        <th style="width: 20%;">Price</th>
                                                        <th style="width: 10%;">Category</th>
                                                        <th style="width: 5%;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($data_package)) : ?>
                                                        <?php foreach ($data_package as $item => $value) : ?>
                                                            <?php if ($value['day']==$key['day']) : ?>
                                                                <tr>
                                                                    <td><?= esc($value['activity']); ?></td>
                                                                    <!-- <td><?= esc($value['activity_type']); ?></td> -->
                                                                    <td><?= esc($value['name']); ?></td>
                                                                    <td><?= esc($value['description']); ?></td>
                                                                    <td><?= 'Rp' . number_format(esc($value['price']), 0, ',', '.'); ?> </td>
                                                                    <td>
                                                                            <?php if ($value['category'] == 0): ?>
                                                                                Group
                                                                            <?php elseif ($value['category'] == 1): ?>
                                                                                Individu
                                                                            <?php elseif ($value['category'] == 2): ?>
                                                                                Shopping not include
                                                                            <?php endif; ?>    
                                                                    </td>
                                                                    <td>
                                                                        <!-- <a class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a> -->
                                                                        <!-- <a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a> -->

                                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                                            <form action="<?= base_url('dashboard/packageday/delete/').$value['package_id']; ?>" method="post" class="d-inline">
                                                                                <?= csrf_field(); ?>
                                                                                <input type="hidden" name="package_id" value="<?= esc($value['package_id']); ?>">
                                                                                <input type="hidden" name="day" value="<?= esc($value['day']); ?>">
                                                                                <input type="hidden" name="activity" value="<?= esc($value['activity']); ?>">
                                                                                <input type="hidden" name="description" value="<?= esc($value['description']); ?>">
                                                                                <input type="hidden" name="_method" value="DELETE">
                                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?');"><i class="fa fa-times"></i></button>
                                                                            </form>
                                                                        </div>
                                                                    </td> 
                                                                </tr>  
                                                            <?php endif; ?>
                
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
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
            <?php endif; ?>
        </div>

    </div>
</section>


<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://cdn.jsdelivr.net/npm/filepond-plugin-media-preview@1.0.11/dist/filepond-plugin-media-preview.min.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="<?= base_url('assets/js/extensions/form-element-select.js'); ?>"></script>

<!-- <script>
    $('#datepicker_start').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
    $('#datepicker_end').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
</script> -->
<script>
    // const myModal = document.getElementById('videoModal');
    // const videoSrc = document.getElementById('video-play').getAttribute('data-src');

    // myModal.addEventListener('shown.bs.modal', () => {
    //     // console.log(videoSrc);
    //     document.getElementById('video').setAttribute('src', videoSrc);
    // });
    // myModal.addEventListener('hide.bs.modal', () => {
    //     document.getElementById('video').setAttribute('src', '');
    // });

    function checkRequired(event) {
        if (!$('#geo-json').val()) {
            event.preventDefault();
            Swal.fire('Please select location for the New Package');
        }
    }
</script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginMediaPreview,
    );

    // Get a reference to the file input element
    const photo = document.querySelector('input[id="gallery"]');
    const video = document.querySelector('input[id="video"]');

    // Create a FilePond instance
    const pond = FilePond.create(photo, {
        imageResizeTargetHeight: 720,
        imageResizeUpscale: false,
        credits: false,
    });
    const vidPond = FilePond.create(video, {
        credits: false,
    })

    <?php if ($edit && count($data['gallery']) > 0) : ?>
        pond.addFiles(
            <?php foreach ($data['gallery'] as $g) : ?> `<?= base_url('media/photos/package/' . $g); ?>`,
            <?php endforeach; ?>
        );
    <?php endif; ?>
    pond.setOptions({
        server: '/upload/photo'
    });

    <?php if ($edit && $data['video_url'] != null) : ?>
        vidPond.addFile(`<?= base_url('media/videos/' . $data['video_url']); ?>`)
    <?php endif; ?>
    vidPond.setOptions({
        server: '/upload/video'
    });
</script>

<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	// Append table with add row form on add new button click
    $(".add-new").click(function(){
		$(this).attr("disabled", "disabled");
		var index = $("table tbody tr:last-child").index();
        var row = '<tr>' +
            '<td><input type="text" class="form-control" name="activity" id="activity"></td>' +
            '<td><input type="text" class="form-control" name="activity_type" id="activity_type"></td>' +
            '<td><input type="text" class="form-control" name="object" id="object"></td>' +
            '<td><input type="text" class="form-control" name="description" id="description"></td>' +
			'<td>' + actions + '</td>' +
        '</tr>';
    	$("table").append(row);		
		$("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
    });
	// Add row on add button click
	$(document).on("click", ".add", function(){
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"]');
        input.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
                $(this).removeClass("error");
            }
		});
		$(this).parents("tr").find(".error").first().focus();
		if(!empty){
			input.each(function(){
				$(this).parent("td").html($(this).val());
			});			
			$(this).parents("tr").find(".add, .edit").toggle();
			$(".add-new").removeAttr("disabled");
		}		
    });
	// Edit row on edit button click
	$(document).on("click", ".edit", function(){		
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
			$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
		});		
		$(this).parents("tr").find(".add, .edit").toggle();
		$(".add-new").attr("disabled", "disabled");
    });
	// Delete row on delete button click
	$(document).on("click", ".delete", function(){
        $(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
    });
});
</script>

<?= $this->endSection() ?>