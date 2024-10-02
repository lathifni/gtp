<?php
$uri = service('uri')->getSegments();
$uri1 = $uri[1] ?? 'index';
$uri2 = $uri[2] ?? '';
$uri3 = $uri[3] ?? '';
?>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <!-- Sidebar Header -->
        <?= $this->include('web/layouts/sidebar_header'); ?>

        <!-- Sidebar -->
        <div class="sidebar-menu">
            <div class="d-flex flex-column">
        
                <?php if (logged_in()) : ?>
                    <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                        <img src="<?= base_url('media/photos/talao.jpg'); ?>" alt="" srcset="">
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

                    <li class="sidebar-item <?= ($uri1 == 'index') ? 'active' : '' ?>">
                        <a href="/web" class="sidebar-link">
                            <i class="fa-solid fa-house"></i><span>Home</span>
                        </a>
                    </li>

                    <!-- <li class="sidebar-item <? //= ($uri1 == 'tracking') ? 'active' : '' 
                                                    ?> has-sub"> -->
                    <li class="sidebar-item has-sub">
                        <a href="" class="sidebar-link">
                            <i class="fa-solid fa-star"></i><span>Attraction</span>
                        </a>

                        <ul class="submenu <?= ($uri1 == 'tracking' || $uri1 == 'talao') ? 'active' : '' ?>">
                            <li class="submenu-item <?= ($uri1 == 'tracking') ? 'active' : '' ?>" id="at-list">
                                <a href="<?= base_url('/web/tracking'); ?>"><i class="fa-solid fa-bridge-water me-3"></i>Tracking Mangrove</a>
                            </li>
                            <li class="submenu-item <?= ($uri1 == 'talao') ? 'active' : '' ?>" id="at-list">
                                <a href="<?= base_url('/web/talao'); ?>"><i class="fa-solid fa-water me-3"></i>Water Attractions</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="sidebar-item <? //= ($uri1 == 'tracking') ? 'active' : '' 
                                                    ?>">
                        <a href="<? //= base_url('/web/tracking'); 
                                    ?>" class="sidebar-link">
                            <i class="fa-solid fa-bridge-water"></i><span>Tracking Mangrove</span>
                        </a>
                    </li>

                    <li class="sidebar-item <? //= ($uri1 == 'talao') ? 'active' : '' 
                                            ?>">
                        <a href="<? //= base_url('/web/talao'); 
                                    ?>" class="sidebar-link">
                            <i class="fa-solid fa-water"></i><span>Water Attractions</span>
                        </a>
                    </li> -->

                    <li class="sidebar-item <?= ($uri1 == 'event') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/event'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-bullhorn"></i><span>Event</span>
                        </a>
                    </li>

                    <!-- Package -->
                    <li class="sidebar-item <?= ($uri1 == 'package') ? 'active' : '' ?> has-sub">
                        <a href="" class="sidebar-link">
                            <i class="fa-solid fa-square-poll-horizontal"></i><span>Package</span>
                        </a>

                        <ul class="submenu <?= ($uri1 == 'package') ? 'active' : '' ?>">
                            <!-- List Package -->
                            <li class="submenu-item" id="pa-list">
                                <a href="<?= base_url('/web/package'); ?>"><i class="fa-solid fa-list me-3"></i>List Package</a>
                            </li>
                            <li class="submenu-item has-sub" id="pa-search">
                                <a data-bs-toggle="collapse" href="#subsubmenu" role="button" aria-expanded="false" aria-controls="subsubmenu" class="collapse"><i class="fa-solid fa-magnifying-glass me-3"></i>Search</a>
                                <ul class="subsubmenu collapse" id="subsubmenu">
                                    <!-- Package by Name -->
                                    <li class="submenu-item submenu-marker" id="pa-by-name">
                                        <a data-bs-toggle="collapse" href="#searchNamePA" role="button" aria-expanded="false" aria-controls="searchNamePA"><i class="fa-solid fa-arrow-down-a-z me-3"></i>By Name</a>
                                        <div class="collapse mb-3" id="searchNamePA">
                                            <div class="d-grid gap-2">
                                                <input type="text" name="namePA" id="namePA" class="form-control" placeholder="Name" aria-label="Recipient's username" aria-describedby="button-addon2" autocomplete="off">
                                                <button class="btn btn-outline-primary" type="submit" id="button-addon2" onclick="findByName('PA')">
                                                    <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">search</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- Package by Type -->
                                    <li class="submenu-item submenu-marker" id="pa-by-type">
                                        <a data-bs-toggle="collapse" href="#searchTypePA" role="button" aria-expanded="false" aria-controls="searchTypePA"><i class="fa-solid fa-check-to-slot me-3"></i>By Type</a>
                                        <div class="collapse mb-3" id="searchTypePA">
                                            <div class="d-grid">
                                                <script>
                                                    getType();
                                                </script>
                                                <fieldset class="form-group">
                                                    <select class="form-select" id="typePASelect">
                                                    </select>
                                                </fieldset>
                                                <button class="btn btn-outline-primary" type="submit" id="button-addon2" onclick="findByType('PA')">
                                                    <span class="material-icons" style="font-size: 1.5rem; vertical-align: bottom">search</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item <?= ($uri1 == 'homestay') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/homestay'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-bed"></i><span>Homestay</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($uri1 == 'reservation') || ($uri1 == 'detailreservation') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/reservation'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-calendar"></i><span>Reservation</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?= ($uri1 == 'ulakan') ? 'active' : '' ?>">
                        <a href="<?= base_url('/web/ulakan'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-map"></i><span>Explore Ulakan</span>
                        </a>
                    </li>

                    <?php if (in_groups(['admin'])) : ?>
                        <li class="sidebar-item">
                            <a href="<?= base_url('dashboard/gtp'); ?>" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i><span>Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="sidebar-item">
                        <div class="d-flex justify-content-around">
                            <a href="https://www.instagram.com/green_talao_park/" class="sidebar-link" target="_blank">
                                <i class="fa-brands fa-instagram"></i><span>Instagram</span>
                            </a>
                            <a href="https://www.tiktok.com/@greentalaopark009" class="sidebar-link" target="_blank">
                                <i class="fa-brands fa-tiktok"></i><span>TikTok</span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>