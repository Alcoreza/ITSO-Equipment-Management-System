<header class="hero-section text-center text-white d-flex align-items-center justify-content-center">
    <div class="overlay"></div>
    <div class="content">
        <h1 class="fw-bold display-3">Aling Basyang’s Sisigan</h1>
        <p class="fst-italic mb-0">"SISIGuraduhin kong masasarapan ka!"</p>
    </div>
</header>

<main class="container text-center my-5">
    <section class="p-5 rounded bg-white shadow-sm">
        <h2 class="text-orange display-6 fw-bold mb-3">Welcome, <?= esc($name); ?>!</h2>
        <p class="lead mt-4 mb-4 text-muted">
            Experience the mouthwatering flavors of our signature sisig dishes — freshly prepared and served with love.
        </p>
        <p class="fs-5 text-dark">
            Explore our menu of delicious meals and discover why Aling Basyang’s Sisigan has become a local favorite.
        </p>
        <a href="<?= base_url('products'); ?>" class="btn btn-orange btn-lg mt-3 px-4 py-2">
            See Our Products
        </a>
    </section>
</main>