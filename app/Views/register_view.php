<div class="container py-5" style="margin-top: 120px;">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <!-- Card -->
            <div class="auth-card p-4 p-md-5">

                <h2 class="auth-title mb-4 text-center">Create an Account</h2>
                <p class="auth-sub text-center mb-4">
                    Register as a Student or Associate to borrow and reserve equipment.
                </p>

                <!-- Demo Session Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('register/submit') ?>" method="post" novalidate>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control" name="fullname" required>
                        <div class="invalid-feedback">Please enter your full name.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback">Enter a valid email address.</div>
                    </div>

                    <!-- User Role -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Type</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select type</option>
                            <option value="student">Student</option>
                            <option value="associate">Associate</option>
                        </select>
                        <div class="invalid-feedback">Please select a valid role.</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="reg_password" name="password" minlength="8"
                            required>
                        <div class="invalid-feedback">Password must be at least 8 characters.</div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_confirm_password" minlength="8" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>

                    <!-- Submit -->
                    <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
                        Create Account
                    </button>

                    <div class="text-center">
                        <a href="<?= base_url('login') ?>" class="text-success fw-semibold">
                            Already have an account? Login
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>