<?php
$uri = service('uri')->getSegments();
$users = in_array('users', $uri);
?>

<?= $this->extend('dashboard/layouts/main'); ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header mb-2">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="card-title">Manage <?= $manage; ?></h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="adminTab">
                    <div class="col">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                            <i class="fa-solid fa-plus me-3"></i>New Announcement
                        </a>
                        <div class="modal fade" id="newAnnouncementModal" tabindex="-1" aria-labelledby="newAnnouncementModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="newAnnouncementModalLabel">New Announcement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?= base_url('dashboard/announcement/add'); ?>" method="post">
                                            <div class="mb-3">
                                                <label for="announcement" class="form-label">Announcement:</label>
                                                <textarea class="form-control" id="announcement" name="announcement" cols="30" rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status:</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="status" value="1" checked>
                                                    <label class="form-check-label" for="status">
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="status" value="2">
                                                    <label class="form-check-label" for="status">
                                                        Non Active
                                                    </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php if (isset($announcementdata) && !empty($announcementdata)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover dt-head-center" id="table-manage">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Announcement</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php $i = 1; ?>
                                    <?php foreach ($announcementdata as $item) : ?>
                                        <tr>
                                            <td><?= esc($i); ?></td>
                                            <td><?= esc($item['id']); ?></td>
                                            <td style="text-align: left; width:500px;"><?= esc($item['announcement']); ?></td>
                                            <td>
                                                <?php
                                                if ($item['status'] == '1') {
                                                    echo 'Active';
                                                } else {
                                                    echo 'Non Active';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-primary" title="More Info" data-bs-toggle="modal" data-bs-target="#announcementDetailModal<?= esc($item['id']) ?>" data-userid="<?= esc($item['id']) ?>">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </button>
                                                <div class="modal fade" id="announcementDetailModal<?= esc($item['id']) ?>" tabindex="-1" aria-labelledby="announcementDetailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="announcementDetailModalLabel">Announcement Detail</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body" id="announcementDetailContent<?= esc($item['id']) ?>">
                                                                <div class="col-md-12 col-12 order-md-first order-last">
                                                                    <div class="mb-4">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <table class="table text-left">
                                                                                    <tbody style="text-align: left;">
                                                                                        <tr>
                                                                                            <td>ID:</td>
                                                                                            <td><?= $item['id'] ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Announcement:</td>
                                                                                            <td><?= $item['announcement'] ?></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                        if ($item['status'] == '1') {
                                                                                            echo ' <tr>
                                                                                            <td>Status:</td>
                                                                                            <td>
                                                                                                <b class="btn btn-sm btn-success">Active</b>
                                                                                            </td>
                                                                                        </tr>';
                                                                                        } else {
                                                                                            echo ' <tr>
                                                                                            <td>Status:</td>
                                                                                            <td>
                                                                                                <b class="btn btn-sm btn-danger">Non Active</b>
                                                                                            </td>
                                                                                        </tr>';
                                                                                        }
                                                                                        ?>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#announcementEditModal<?= esc($item['id']) ?>" data-userid="<?= esc($item['id']) ?>">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="announcementEditModal<?= esc($item['id']) ?>" tabindex="-1" aria-labelledby="announcementEditModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="announcementEditModalLabel">Edit Announcement</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body" style="text-align: left;" id="announcementEditContent<?= esc($item['id']) ?>">
                                                                <form action="<?= base_url('dashboard/announcement/update/') . $item['id']; ?>" method="post">
                                                                    <div class="mb-3">
                                                                        <label for="announcement" class="form-label">Announcement:</label>
                                                                        <textarea class="form-control" id="announcement" name="announcement" cols="30" rows="3"><?= esc($item['announcement']); ?></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="status" class="form-label">Status:</label>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status" id="status1_<?= esc($item['id']) ?>" value="1" <?= $item['status'] == 1 ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="status1_<?= esc($item['id']) ?>">
                                                                                Active
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="status" id="status0_<?= esc($item['id']) ?>" value="2" <?= $item['status'] == 2 ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="status0_<?= esc($item['id']) ?>">
                                                                                Non Active
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" class="btn icon btn-outline-danger mx-1" onclick="deleteObject('<?= esc($item['id']); ?>', '<?= esc($item['announcement']); ?>', <?= ($users) ? 'true' : 'false'; ?>)">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function() {
        $('#table-manage').DataTable({
            columnDefs: [{
                targets: ['_all'],
                className: 'dt-head-center'
            }],
            lengthMenu: [5, 10, 20, 50, 100]
        });

        $('.btn-outline-primary').on('click', function() {
            var announcementId = $(this).data('userid');
            $.ajax({
                url: '<?= base_url('api/users/') ?>' + announcementId,
                type: 'GET',
                success: function(response) {
                    $('#announcementDetailContent' + announcementId).html(response);
                    $('#announcementDetailModal' + announcementId).modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        $('.btn-outline-info').on('click', function() {
            var announcementId = $(this).data('userid');
            $.ajax({
                url: '<?= base_url('api/users/') ?>' + announcementId,
                type: 'GET',
                success: function(response) {
                    $('#announcementEditContent' + announcementId).html(response);
                    $('#announcementEditModal' + announcementId).modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.3/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>