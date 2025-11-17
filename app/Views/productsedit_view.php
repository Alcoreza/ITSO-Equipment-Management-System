<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Edit Product</h1>
    <p class="text-muted">Update the details of this delicious dish</p>
</header>

<main class="container mb-5">
    <div class="col-md-8 mx-auto">
        <section class="p-5 bg-white rounded shadow-sm border border-light">
            <form action="<?= base_url('products/update/' . $product['id']); ?>" method="post"
                enctype="multipart/form-data">
                <!-- Product Image -->
                <div class="text-center mb-4">
                    <?php if (!empty($product['image'])): ?>
                        <img src="<?= base_url('public/img/' . esc($product['image'])); ?>"
                            alt="<?= esc($product['product_name']); ?>" class="img-fluid rounded-4 shadow-sm mb-2"
                            style="max-height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-4 mb-2"
                            style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    <small class="text-muted fst-italic d-block">Current product image</small>
                </div>

                <!-- Product Name -->
                <div class="mb-3">
                    <label for="product_name" class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="form-control"
                        value="<?= esc($product['product_name']); ?>" required>
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                        value="<?= esc($product['price']); ?>" required>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option disabled value="">Select category</option>
                        <option value="Sisig" <?= $product['category'] == 'Sisig' ? 'selected' : ''; ?>>Sisig</option>
                        <option value="Silog Meals" <?= $product['category'] == 'Silog Meals' ? 'selected' : ''; ?>>Silog
                            Meals</option>
                        <option value="Beverages" <?= $product['category'] == 'Beverages' ? 'selected' : ''; ?>>Beverages
                        </option>
                        <option value="Extras" <?= $product['category'] == 'Extras' ? 'selected' : ''; ?>>Extras</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"
                        required><?= esc($product['description']); ?></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">Replace Image (Optional)</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted fst-italic">Leave empty if you don’t want to change the image</small>

                    <input type="hidden" name="old_image" value="<?= esc($product['image']); ?>">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('products'); ?>" class="btn btn-warning rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-orange rounded-pill px-4">
                        <i class="bi bi-save"></i> Update Product
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>