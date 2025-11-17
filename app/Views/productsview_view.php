<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">View Product Details</h1>
    <p class="text-muted">See all information about this menu item</p>
</header>

<main class="container mb-5">
    <div class="col-md-8 mx-auto">
        <section class="p-5 bg-white rounded shadow-sm border border-light">
            <!-- Product Image -->
            <div class="text-center mb-4">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?= base_url('public/img/' . esc($product['image'])); ?>"
                        alt="<?= esc($product['product_name']); ?>" class="img-fluid rounded-4 shadow-sm"
                        style="max-height: 250px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-4" style="height: 250px;">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Details -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" class="form-control" value="<?= esc($product['product_name']); ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Price (₱)</label>
                <input type="text" class="form-control" value="₱<?= number_format($product['price'], 2); ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" class="form-control" value="<?= esc($product['category']); ?>" readonly>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control" rows="4" readonly><?= esc($product['description']); ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between">
                <a href="<?= base_url('products'); ?>" class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="<?= base_url('products/edit/' . $product['id']); ?>" class="btn btn-orange rounded-pill px-4">
                    <i class="bi bi-pencil"></i> Edit Product
                </a>
            </div>
        </section>
    </div>
</main>