<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Desa Wisata</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link href="<?= base_url('media/icon/favicon.svg'); ?>" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Quicksand:wght@600;700&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/lib/animate/animate.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/lightbox/css/lightbox.min.css'); ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/owlcarousel/assets/owl.carousel.min.css'); ?>" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('css/landing-page/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="<?= base_url('css/landing-page/style.css'); ?>" rel="stylesheet" />
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-lg-0 px-4 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="/" class="navbar-brand p-0">
            <img class="img-fluid me-3" src="<?= base_url('media/icon/logo.svg'); ?>" alt="Icon" />
            <h1 class="m-0 text-primary">Tourism Village</h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse py-4 py-lg-0" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
                <a href="#home" class="nav-item nav-link active">Home</a>
                <!-- <a href="/web" class="nav-item nav-link">Explore</a> -->
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="#award" class="nav-item nav-link">Award</a>
            </div>
            <?php if (!logged_in()) : ?>
                <a href="<?= base_url('login'); ?>" class="btn btn-primary">Login</a>
            <?php elseif(logged_in()) : ?>
                <div class="col-1">
                    <?php if (logged_in()) : ?>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <a class="" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar avatar-md">
                                        <img style="width:30px;" src="<?= base_url('media/photos/user'); ?>/<?= user()->user_image; ?>" alt="Face 1" />
                                    </div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="<?= base_url('web/profile'); ?>">My Profile</a>
                                    <a class="dropdown-item" href="<?= base_url('logout'); ?>">Log Out</a>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?= base_url('login'); ?>" class="btn btn-primary">Login</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-dark p-0 mb-5" id="home">
        <div class="row g-0 flex-column-reverse flex-lg-row">
            <div class="col-lg-6 p-0 wow fadeIn" data-wow-delay="0.1s">
                <div class="header-bg h-100 d-flex flex-column justify-content-center p-5">
                    <h2 class="display-6 text-light mb-2">
                        Welcome to
                    </h2>
                    <h1 class="display-4 text-light mb-5">
                        Desa Wisata<br>Green Talao Park<br>(GTP) Ulakan
                    </h1>
                    <div class="d-flex align-items-center pt-4 animated slideInDown">
                        <a href="/web" class="btn btn-primary py-sm-3 px-3 px-sm-5 me-5">Explore</a>
                        <!-- <a href="/web" class="btn btn-primary py-sm-3 px-3 px-sm-5 me-5"
                        >Explore Ulakan</a
                        > -->
                        <button type="button" class="btn-play" data-bs-toggle="modal" data-src="<?= base_url('media/videos/landing_page.mp4'); ?>" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                        <h6 class="text-white m-0 ms-4 d-none d-sm-block">Watch Video</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="owl-carousel header-carousel">
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/carousel-1.jpg'); ?>" alt="" />
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/carousel-2.jpg'); ?>" alt="" />
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="<?= base_url('media/photos/landing-page/carousel-3.jpg'); ?>" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Video Modal Start -->
    <div class="modal modal-video fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Video</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <video src="" class="embed-responsive-item" id="video" controls autoplay>Sorry, your browser doesn't support embedded videos</video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->

    <!-- About Start -->
    <div class="container-xxl py-5" id="about">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p><span class="text-primary me-2">#</span>Welcome To Desa Wisata </p>
                    <h1 class="display-5 mb-4">
                        Why You Should Visit
                        Desa Wisata <br><span class="text-primary">GTP Ulakan</span>
                    </h1>
                    <p class="mb-4">
                        Nagari Ulakan, geographically has natural coastal potential with its estuary or 
                        in local language it is called Talao, has succeeded in turning 15 hectares of 
                        unused land into a tourist village destination with the concept of community based 
                        ecotourism. This area is named Green Talao Park with the theme of ecotourism and 
                        education with a unique attraction, in the form of a 1.8 km long talao/mangrove 
                        tracking track, which is the longest trekking in West Sumatra. Not only as 
                        a recreation area, Green Talao Park also offers various events and tour packages,
                        ranging from local wisdom packages, conservation, natural landscapes, island tours 
                        and culinary tours. Apart from that, this area is also directly integrated with 
                        the center of religious tourism in West Sumatra through intangible cultural sites 
                        provided by the government, namely the tomb of Sheikh Burhanudin, a great Islamic 
                        preacher in the Minang area and the Pieh Island Tourism Park (TWP) area.
                    </p>
                    <h5 class="mb-3">
                        <i class="far fa-check-circle text-primary me-3"></i>Natural Tourism
                    </h5>
                    <h5 class="mb-3">
                        <i class="far fa-check-circle text-primary me-3"></i>Cultural Tourism
                    </h5>
                    <h5 class="mb-3">
                        <i class="far fa-check-circle text-primary me-3"></i>Educational Tourism
                    </h5>
                    <h5 class="mb-3">
                        <i class="far fa-check-circle text-primary me-3"></i>Culinary Tourism
                    </h5>
                    <a class="btn btn-primary py-3 px-5 mt-3" href="/web">Explore</a>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="img-border">
                        <img class="img-fluid right" src="<?= base_url('media/photos/landing-page/bg-about.jpg'); ?>" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Award Start -->
    <div class="container-xxl bg-primary facts my-5 py-5 wow fadeInUp" data-wow-delay="0.1s" id="award">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4 col-lg-4 text-center wow fadeIn" data-wow-delay="0.1s">
                    <img src="<?= base_url('media/photos/landing-page/trophy.png'); ?>" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <h1 class="text-white mb-2" data-toggle="counter-up">300</h1>
                    <p class="text-white mb-0">Top ADWI 2021</p>
                </div>
                <div class="col-md-4 col-lg-4 text-center wow fadeIn" data-wow-delay="0.1s">
                    <img src="<?= base_url('media/photos/landing-page/trophy.png'); ?>" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <h1 class="text-white mb-2" data-toggle="counter-up">50</h1>
                    <p class="text-white mb-0">Top ADWI 2022</p>
                </div>
                <div class="col-md-4 col-lg-4 text-center wow fadeIn" data-wow-delay="0.3s">
                    <img src="<?= base_url('media/photos/landing-page/trophy.png'); ?>" alt="" style="filter: invert(100%); max-width: 5em">
                    <h1 class="text-white mb-2" data-toggle="counter-up">1</h1>
                    <p class="text-white mb-0">Harapan I Kelembagaan ADWI 2022</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Award End -->

    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-9 col-md-6">
                    <h5 class="text-light mb-4">Address</h5>
                    <p class="mb-2">
                        <i class="fa fa-map-marker-alt me-3"></i>Nagari Ulakan, Ulakan Tapakis, Kabupaten Padang Pariaman, Sumatera Barat
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-phone-alt me-3"></i>082383985824
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-envelope me-3"></i>adikurniawan.gtp@gmail.com
                    </p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="https://www.instagram.com/Green_Talao_Park/"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://web.facebook.com/Ekowisata%20dan%20edukasi%20Nagari%20Ulakan?_rdc=1&_rdr"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Links</h5>
                    <a class="btn btn-link" href="#home">Home</a>
                    <!-- <a class="btn btn-link" href="/web">Explore</a> -->
                    <a class="btn btn-link" href="#about">About</a>
                    <a class="btn btn-link" href="#award">Award</a>
                    <a class="btn btn-link" href="<?= base_url('login'); ?>">Login</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Mutiara Raudhatul Jannah</a>. All
                        Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/lib/wow/wow.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/easing/easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/waypoints/waypoints.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/counterup/counterup.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?= base_url('assets/lib/lightbox/js/lightbox.min.js'); ?>"></script>

    <!-- Template Javascript -->
    <script src="<?= base_url('js/landing-page.js'); ?>"></script>
</body>

</html>