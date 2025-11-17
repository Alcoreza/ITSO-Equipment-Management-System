<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">Edit User Account</h1>
    <p class="text-muted">Update user details and credentials</p>
</header>

<main class="container mb-5">
    <section class="p-5 bg-white rounded-4 shadow-sm col-md-6 mx-auto">
        <form id="editUserForm" action="<?= base_url('users/update/' . $user['id']) ?>" method="post">
            <!-- Username -->
            <div class="form-group mb-3 text-start">
                <label for="username" class="form-label fw-semibold text-muted">Username</label>
                <input type="text" name="username" id="username" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['username']) ?>" required>
            </div>

            <!-- Password -->
            <div class="form-group mb-3 text-start">
                <label for="password" class="form-label fw-semibold text-muted">Password</label>
                <input type="password" name="password" id="password" class="form-control rounded-pill shadow-sm"
                    placeholder="Leave blank to keep current password">
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-3 text-start">
                <label for="confirmpassword" class="form-label fw-semibold text-muted">Confirm Password</label>
                <input type="password" id="confirmpassword" class="form-control rounded-pill shadow-sm"
                    placeholder="Re-enter password if changing">
                <div id="passwordError" class="text-danger small mt-1" style="display:none;">
                    Passwords do not match.
                </div>
            </div>

            <!-- First Name -->
            <div class="form-group mb-3 text-start">
                <label for="first_name" class="form-label fw-semibold text-muted">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['first_name']) ?>" required>
            </div>

            <!-- Middle Name -->
            <div class="form-group mb-3 text-start">
                <label for="middle_name" class="form-label fw-semibold text-muted">Middle Name</label>
                <input type="text" name="middle_name" id="middle_name" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['middle_name']) ?>">
            </div>

            <!-- Last Name -->
            <div class="form-group mb-3 text-start">
                <label for="last_name" class="form-label fw-semibold text-muted">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['last_name']) ?>" required>
            </div>

            <!-- Suffix -->
            <div class="form-group mb-3 text-start">
                <label for="suffix" class="form-label fw-semibold text-muted">Suffix</label>
                <input type="text" name="suffix" id="suffix" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['suffix']) ?>" placeholder="e.g., Jr., III (optional)">
            </div>

            <!-- Email -->
            <div class="form-group mb-3 text-start">
                <label for="email" class="form-label fw-semibold text-muted">Email</label>
                <input type="email" name="email" id="email" class="form-control rounded-pill shadow-sm"
                    value="<?= esc($user['email']) ?>" required>
            </div>

            <!-- Role -->
            <div class="form-group mb-4 text-start">
                <label for="role" class="form-label fw-semibold text-muted">Role</label>
                <select name="role" id="role" class="form-select rounded-pill shadow-sm">
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                    <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="text-center">
                <button type="submit" class="btn btn-orange rounded-pill px-4 py-2 shadow-sm">
                    <i class="bi bi-save"></i> Save Changes
                </button>
                <a href="<?= base_url('users'); ?>" class="btn btn-warning rounded-pill px-4 py-2 shadow-sm ms-2">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </section>
</main>