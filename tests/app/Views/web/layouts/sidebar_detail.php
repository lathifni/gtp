<?php
$uri = service('uri')->getSegments();
$uri1 = $uri[1] ?? 'index';
$uri2 = $uri[2] ?? '';
$uri3 = $uri[3] ?? '';

// dd($uri, $uri1, $uri2, $uri3);
?>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <?= $this->include('web/layouts/sidebar_header'); ?>
        <div class="sidebar-menu">
            <div class="d-flex flex-column">
                <?php if (logged_in()) : ?>
                    <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                        <img src="<?= base_url('media/photos/user/'); ?><?= user()->user_image; ?>" alt="" srcset="">
                    </div>
                    <div class="p-2 text-center">
                        <?php if (!empty(user()->fullname)) : ?>
                            Hello, <span class="fw-bold"><?= user()->fullname; ?></span> <br> <span class="text-muted mb-0">@<?= user()->username; ?></span>
                        <?php else : ?>
                            Hello, <span class="fw-bold">@<?= user()->username; ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                        <img src="<?= base_url('media/photos/talao.jpg'); ?>" alt="" srcset="">
                    </div>
                    <div class="p-2 d-flex justify-content-center">Hello, Visitor</div>
                <?php endif; ?>

                <ul class="menu">
                    <li class="sidebar-item">
                        <a href="/web" onclick="self.close()" class="sidebar-link">
                            <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">arrow_back</span> <span>Back to Home</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>