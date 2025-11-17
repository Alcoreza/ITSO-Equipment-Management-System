<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Our Products</h1>
    <p class="text-muted">Manage and showcase your delicious sisig dishes</p>
</header>

<main class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('products/add'); ?>" class="btn btn-orange px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    <?php if (!empty($products)): ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="user-card p-4 shadow-sm rounded-4">

                        <!-- Product Image -->
                        <div class="mb-3 text-center">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= base_url('public/img/' . esc($product['image'])); ?>"
                                    alt="<?= esc($product['product_name']); ?>" class="img-fluid rounded-3 shadow-sm"
                                    style="height: 180px; object-fit: cover; width: 100%;">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-3"
                                    style="height: 180px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <h5 class="fw-bold mb-1 text-orange"><?= esc($product['product_name']); ?></h5>
                        <p class="text-muted mb-2"><?= esc($product['category']); ?></p>
                        <h6 class="fw-semibold mb-3">₱<?= number_format($product['price'], 2); ?></h6>

                        <!-- Description -->
                        <p class="text-muted small mb-4" style="min-height: 48px;">
                            <?= esc(strlen($product['description']) > 80 ? substr($product['description'], 0, 80) . '...' : $product['description']); ?>
                        </p>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('products/view/' . $product['id']); ?>"
                                class="btn btn-sm btn-outline-success rounded-pill px-3">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="<?= base_url('products/edit/' . $product['id']); ?>"
                                class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn"
                                data-url="<?= base_url('products/delete/' . $product['id']); ?>">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted mt-5">No products found.</p>
    <?php endif; ?>
</main>