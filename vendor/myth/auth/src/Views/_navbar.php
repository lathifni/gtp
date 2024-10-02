<nav class="navbar navbar-expand-lg bg-white navbar-light fixed-top py-lg-0 px-4 px-lg-5 wow fadeIn">
    <a href="/" class="navbar-brand p-0" style="width:85%;">
        <img class="img-fluid me-3" src="<?= base_url('media/icon/logo.svg'); ?>" alt="Icon" />
        <h2 class="m-0 text-primary">Tourism Village</h2>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse py-4 py-lg-0 float-right" id="navbarCollapse">
        <div class="navbar-nav ms-auto ">
            <!-- <li class="nav-item active"> -->
                <a href="<?= base_url('web'); ?>" class="nav-item nav-link">Explore</a>
                <a href="<?= base_url(); ?>" class="nav-item nav-link">About</a>
                <a href="<?= base_url('login'); ?>" class="nav-item nav-link">Login</a>
            <!-- </li> -->
        </div>
    </div>
</nav>