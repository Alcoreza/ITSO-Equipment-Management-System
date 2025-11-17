<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Viewing User Account</h1>
    <p class="text-muted">Detailed information of the selected user</p>
</header>

<main class="container mb-5">
    <section class="p-5 bg-white rounded-4 shadow-sm col-md-6 mx-auto">

        <div class="form-group mb-3 text-start">
            <label for="username" class="form-label fw-semibold text-muted">Username</label>
            <input type="text" id="username" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['username']) ?>">
        </div>

        <!-- Split full name into individual fields -->
        <div class="form-group mb-3 text-start">
            <label for="first_name" class="form-label fw-semibold text-muted">First Name</label>
            <input type="text" id="first_name" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['first_name']) ?>">
        </div>

        <div class="form-group mb-3 text-start">
            <label for="middle_name" class="form-label fw-semibold text-muted">Middle Name</label>
            <input type="text" id="middle_name" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['middle_name']) ?>">
        </div>

        <div class="form-group mb-3 text-start">
            <label for="last_name" class="form-label fw-semibold text-muted">Last Name</label>
            <input type="text" id="last_name" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['last_name']) ?>">
        </div>

        <?php if (!empty($user['suffix'])): ?>
            <div class="form-group mb-3 text-start">
                <label for="suffix" class="form-label fw-semibold text-muted">Suffix</label>
                <input type="text" id="suffix" class="form-control rounded-pill shadow-sm" readonly
                    value="<?= esc($user['suffix']) ?>">
            </div>
        <?php endif; ?>

        <div class="form-group mb-3 text-start">
            <label for="email" class="form-label fw-semibold text-muted">Email</label>
            <input type="email" id="email" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['email']) ?>">
        </div>

        <div class="form-group mb-3 text-start">
            <label for="role" class="form-label fw-semibold text-muted">Role</label>
            <input type="text" id="role" class="form-control rounded-pill shadow-sm" readonly
                value="<?= ucfirst(esc($user['role'])) ?>">
        </div>

        <div class="form-group mb-3 text-start">
            <label for="created_at" class="form-label fw-semibold text-muted">Created At</label>
            <input type="text" id="created_at" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['created_at']) ?>">
        </div>

        <div class="form-group mb-4 text-start">
            <label for="updated_at" class="form-label fw-semibold text-muted">Last Updated</label>
            <input type="text" id="updated_at" class="form-control rounded-pill shadow-sm" readonly
                value="<?= esc($user['updated_at']) ?>">
        </div>

        <div class="text-center">
            <a href="<?= base_url('users'); ?>" class="btn btn-orange rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left"></i> Back to Users List
            </a>
        </div>
    </section>
</main>