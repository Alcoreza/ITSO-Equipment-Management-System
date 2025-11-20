<!-- app/Views/include/nav_view.php -->
<nav class="navbar navbar-expand-lg navbar-modern">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="<?= site_url('/') ?>">
            <span class="brand-text">ITSO EMS</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('about') ?>">About</a>
                </li>

                <!-- mobile-only links -->
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="<?= site_url('register') ?>">Register</a>
                </li>

                <!-- CTAs on large screens -->
                <li class="nav-item d-none d-lg-block ms-3">
                    <a class="btn btn-outline-light btn-sm" href="<?= site_url('register') ?>">Register</a>
                </li>
                <li class="nav-item d-none d-lg-block ms-2">
                    <a class="btn btn-primary btn-sm" href="<?= site_url('login') ?>">Login</a>
                </li>
            </ul>
        </div>

    </div>
</nav>