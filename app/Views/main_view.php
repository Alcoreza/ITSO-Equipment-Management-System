<!-- app/Views/main_view.php -->

<!-- FULL SCREEN HERO (no container wrapper) -->
<div class="hero-full hero-bg d-flex flex-column justify-content-center align-items-center text-center">

    <div class="hero-inner">
        <div class="eyebrow animated-up">IT Services Office — FEU</div>

        <h1 class="display-5 hero-title animated-up delay-1">
            ITSO Equipment Management System
        </h1>

        <p class="lead hero-sub animated-up delay-2" style="max-width:760px;">
            Borrow, return, and reserve IT equipment quickly and securely. Streamlined workflows for students,
            associates, and ITSO personnel — with automated confirmations and inventory tracking.
        </p>

        <div class="hero-ctas animated-up delay-3">
            <a href="<?= site_url('login') ?>" class="btn btn-primary btn-lg me-2">Login</a>
            <a href="<?= site_url('register') ?>" class="btn btn-outline-light btn-lg">Register</a>
        </div>
    </div>

    <!-- SVG wave divider -->
    <!-- Smooth Hero Wave (inside hero) -->
    <svg class="hero-wave" viewBox="0 0 1440 140" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true" focusable="false">
        <path d="M0,32 C360,120 1080,0 1440,88 L1440,140 L0,140 Z" fill="#ffffff"></path>
    </svg>


    <!-- scroll indicator -->
    <a href="#below-hero" class="scroll-indicator" aria-label="Scroll down">
        <span class="mouse">
            <span class="wheel"></span>
        </span>
    </a>
</div>

<!-- PAGE CONTENT BELOW HERO -->
<div id="below-hero" class="container py-5">

    <!-- ABOUT / HERO SUMMARY (glass card) -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="glass-card p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="h4 mb-2">About the IT Services Office</h2>
                        <p class="text-muted mb-0">
                            The IT Services Office (ITSO) supports academic and administrative activities by managing
                            equipment such as laptops,
                            projectors, tablets, cameras, and lab keys. This system helps the office track assets,
                            handle borrowing/returns,
                            and manage reservations to ensure availability and accountability.
                        </p>
                    </div>

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="badge-soft">Official ITSO</div>
                        <div class="mt-2 text-muted small">For FEU campus equipment management</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES GRID (icon cards) -->
    <div class="row text-center mb-5">
        <h3 class="fw-bold mb-4">Key Features</h3>

        <div class="col-md-4 mb-4">
            <div class="feature-card h-100 p-4">
                <div class="feature-icon">📦</div>
                <h5>Borrow Equipment</h5>
                <p class="text-muted">
                    Request devices for classroom use or events. Borrow transactions are recorded and confirmed via
                    email.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="feature-card h-100 p-4">
                <div class="feature-icon">🔧</div>
                <h5>Return & Condition Tracking</h5>
                <p class="text-muted">
                    Returns are logged and checked. Damaged or unusable items are flagged for maintenance or disposal.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="feature-card h-100 p-4">
                <div class="feature-icon">📅</div>
                <h5>Reservations</h5>
                <p class="text-muted">
                    Associates can reserve equipment at least one day in advance. Reservations can be modified or
                    canceled.
                </p>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS — Modern Numbered Stepper (No Icons) -->
    <section id="how-it-works" class="mb-5">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center">How it works</h4>

            <div class="how-steps">

                <div class="step">
                    <div class="step-left">
                        <div class="step-badge">
                            <div class="step-num">1</div>
                        </div>
                    </div>
                    <div class="step-body">
                        <h5 class="mb-1">Register / Login</h5>
                        <p class="text-muted mb-0">
                            Create an account or sign in using your FEU credentials. Email verification ensures secure
                            access.
                        </p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-left">
                        <div class="step-badge">
                            <div class="step-num">2</div>
                        </div>
                    </div>
                    <div class="step-body">
                        <h5 class="mb-1">Request Equipment</h5>
                        <p class="text-muted mb-0">
                            Choose equipment, check availability, set dates, and submit a borrow or reservation request.
                        </p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-left">
                        <div class="step-badge">
                            <div class="step-num">3</div>
                        </div>
                    </div>
                    <div class="step-body">
                        <h5 class="mb-1">Approval & Confirmation</h5>
                        <p class="text-muted mb-0">
                            ITSO personnel review your request. You’ll receive an email confirmation once approved.
                        </p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-left">
                        <div class="step-badge">
                            <div class="step-num">4</div>
                        </div>
                    </div>
                    <div class="step-body">
                        <h5 class="mb-1">Pickup</h5>
                        <p class="text-muted mb-0">
                            Present your transaction ID at the ITSO office and receive the equipment with its required
                            accessories.
                        </p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-left">
                        <div class="step-badge">
                            <div class="step-num">5</div>
                        </div>
                    </div>
                    <div class="step-body">
                        <h5 class="mb-1">Return</h5>
                        <p class="text-muted mb-0">
                            Return the equipment on or before the deadline. ITSO will inspect and log the condition upon
                            return.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


</div>