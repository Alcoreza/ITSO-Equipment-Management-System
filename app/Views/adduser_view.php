<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Add New User</h1>
    <p class="text-muted">Fill in all required fields to create a new account</p>
</header>

<main class="container mb-5">
    <section class="bg-white p-5 rounded-4 shadow-sm col-md-6 mx-auto">
        <form id="addUserForm" method="post" action="<?= base_url('users/insert') ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Username</label>
                <input type="text" name="username" class="form-control rounded-pill shadow-sm" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Password</label>
                <input type="password" name="password" id="password" class="form-control rounded-pill shadow-sm"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Confirm Password</label>
                <input type="password" id="confirmpassword" class="form-control rounded-pill shadow-sm" required>
                <div id="passwordError" class="text-danger small mt-1" style="display:none;">
                    Passwords do not match.
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">First Name</label>
                    <input type="text" name="first_name" class="form-control rounded-pill shadow-sm" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control rounded-pill shadow-sm">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Last Name</label>
                    <input type="text" name="last_name" class="form-control rounded-pill shadow-sm" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-muted">Suffix</label>
                    <input type="text" name="suffix" class="form-control rounded-pill shadow-sm">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Email</label>
                <input type="email" name="email" class="form-control rounded-pill shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted">Role</label>
                <select name="role" class="form-select rounded-pill shadow-sm">
                    <option value="staff" selected>Staff</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-orange rounded-pill px-4 py-2 shadow-sm">
                    <i class="bi bi-person-plus"></i> Add User
                </button>
                <a href="<?= base_url('users'); ?>" class="btn btn-secondary rounded-pill px-4 py-2 ms-2 shadow-sm">
                    <i class="bi bi-arrow-left"></i> Cancel
                </a>
            </div>
        </form>
    </section>
</main>