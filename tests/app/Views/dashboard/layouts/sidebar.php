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
                <div class="d-flex justify-content-center avatar avatar-xl me-3" id="avatar-sidebar">
                    <img src="<?= base_url('media/photos/talao.jpg'); ?>" alt="" srcset="">
                </div>

                <?php if (logged_in()) : ?>
                    <div class="p-2 text-center">
                        <?php if (!empty(user()->fullname)) : ?>
                            Hello, <span class="fw-bold"><?= user()->fullname; ?></span> <br> <span class="text-muted mb-0">@<?= user()->username; ?></span>
                        <?php else : ?>
                            Hello, <span class="fw-bold">@<?= user()->username; ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="p-2 d-flex justify-content-center">Hello, Visitor</div>
                <?php endif; ?>

                <ul class="menu">
                    <li class="sidebar-item">
                        <a href="<?= base_url('web'); ?>" class="sidebar-link">
                            <i class="fa-solid fa-house"></i><span> Home</span>
                        </a>
                    </li>


                    <?php if (in_groups(['admin']) || in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item <?= ($uri1 == 'gtp') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/gtp');
                                        ?>" class="sidebar-link">
                                <i class="fa-brands fa-pagelines"></i><span>Manage GTP</span>
                            </a>
                        </li>
                    <?php endif;
                    ?>

                    <?php if (in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item <?= ($uri1 == 'users') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/users');
                                        ?>" class="sidebar-link">
                                <i class="fa fa-users"></i><span>Manage Users</span>
                            </a>
                        </li>
                    <?php endif;
                    ?>

                    <?php if (in_groups(['admin']) || in_groups(['master'])) :
                    ?>
                        <li class="sidebar-item <?= ($uri1 == 'attraction') ? 'active' : ''
                                                ?>">
                            <a href="<?= base_url('dashboard/attraction');
                                        ?>" class="sidebar-link">
                                <i class="fa-solid fa-star"></i><span>Manage Attraction</span>
                            </a>
                        </li>
                    <?php endif;
                    ?>

                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                        <li class="sidebar-item <?= ($uri1 == 'event') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/event'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bullhorn"></i><span>Manage Event</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                        <li class="sidebar-item <?= ($uri1 == 'homestay') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/homestay'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bed"></i><span>Manage Homestay</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- <?php if (in_groups(['admin']) || in_groups(['master'])) : ?>
                        <li class="sidebar-item <?= ($uri1 == 'reservation') ? 'active' : '' ?>">
                            <a href="<?= base_url('dashboard/managereservation'); ?>" class="sidebar-link">
                                <i class="fa-solid fa-bullhorn"></i><span>Manage Reservation</span>
                            </a>
                        </li>
                    <?php endif; ?> -->
                    
                    <?php if (in_groups(['admin']) || in_groups(['master'])) :
                        ?>
                        <li class="sidebar-item has-sub">
                            <a href="" class="sidebar-link">
                                <i class="fa-solid fa-square-poll-horizontal"></i><span>Manage Object</span>
                            </a>
                            <ul class="submenu">
                                <!-- Facility -->
                                <li class="submenu-item <?= ($uri1 == 'facility') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/facility');?>"><i class="fa-solid fa-map-pin"></i> Facility</a>
                                </li>

                                <!-- Culinary Place -->
                                <li class="submenu-item <?= ($uri1 == 'culinaryplace') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/culinaryplace');?>"><i class="fa-solid fa-utensils"></i> Culinary Place</a>
                                </li>

                                <!-- Worship Place -->
                                <li class="submenu-item <?= ($uri1 == 'worshipplace') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/worshipplace');?>"><i class="fa-solid fa-mosque"></i> Worship Place</a>
                                </li>

                                <!-- Souvenir Place -->
                                <li class="submenu-item <?= ($uri1 == 'souvenirplace') ? 'active' : '' ?>" id="pa-list">
                                    <a href="<?= base_url('dashboard/souvenirplace');?>"><i class="fa-solid fa-cart-shopping"></i> Souvenir Place</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif;
                    ?>
                </ul>
            </div>

        </div>
    </div>
</div>