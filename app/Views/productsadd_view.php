<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Add New Product</h1>
    <p class="text-muted">Create and showcase a new dish on Aling Basyang’s menu</p>
</header>

<main class="container mb-5">
    <div class="col-md-8 mx-auto">
        <section class="p-5 bg-white rounded shadow-sm border border-light">
            <form action="<?= base_url('products/insert'); ?>" method="post" enctype="multipart/form-data">
                <!-- Product Name -->
                <div class="mb-3">
                    <label for="product_name" class="form-label fw-semibold">Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="form-control"
                        placeholder="Enter product name" required>
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label for="price" class="form-label fw-semibold">Price (₱)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control"
                        placeholder="Enter price" required>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option selected disabled value="">Select category</option>
                        <option value="Sisig">Sisig</option>
                        <option value="Silog Meals">Silog Meals</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Extras">Extras</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"
                        placeholder="Describe this product (ingredients, serving, etc.)" required></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    <small class="text-muted fst-italic">Upload a clear photo of the product (JPG, PNG, or WebP)</small>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= base_url('products'); ?>" class="btn btn-warning rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-orange rounded-pill px-4">
                        <i class="bi bi-save"></i> Save Product
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>