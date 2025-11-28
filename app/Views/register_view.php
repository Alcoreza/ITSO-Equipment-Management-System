<div class="auth-page">
    <div class="auth-card">
        <!-- Badge -->
        <div class="auth-badge mb-3">
            <div class="auth-badge-icon">
                <span>FT</span>
            </div>
            <span class="auth-badge-text">FEU Tech Access</span>
        </div>

        <h1 class="auth-title mb-1 text-start">Create an account</h1>
        <p class="auth-sub mb-3">
            Register as a Student or Associate to borrow and reserve equipment.
        </p>

        <!-- Flash messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-modern alert-modern-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-modern alert-modern-success mb-3">
                <i class="bi bi-check-circle me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" action="<?= base_url('register/submit') ?>" method="post">
            <?= csrf_field() ?>

            <!-- FULL NAME -->
            <div class="mb-3">
                <label class="auth-label" for="fullname">Full name</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" 
                        class="form-control auth-input" 
                        id="fullname" 
                        name="fullname"
                        value="<?= old('fullname') ?>"
                        placeholder="Juan Dela Cruz" 
                        required>
                </div>
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <label class="auth-label" for="reg_email">Email</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" 
                        class="form-control auth-input" 
                        id="reg_email" 
                        name="email"
                        value="<?= old('email') ?>"
                        placeholder="you@fit.edu.ph" 
                        required>
                </div>
            </div>

            <!-- ACCOUNT TYPE -->
            <div class="mb-3">
                <label class="auth-label" for="role">Account type</label>
                <select name="role" id="role" class="form-select auth-select" required>
                    <option value="">Select type</option>
                    <option value="Student" <?= old('role') === 'Student' ? 'selected' : '' ?>>Student</option>
                    <option value="Associate" <?= old('role') === 'Associate' ? 'selected' : '' ?>>Associate</option>
                    <option value="itso" <?= old('role') === 'itso' ? 'selected' : '' ?>>ITSO Personnel</option>
                </select>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label class="auth-label" for="reg_password">Password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" 
                        class="form-control auth-input" 
                        id="reg_password" 
                        name="password"
                        placeholder="Create a password" 
                        required>
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-4">
                <label class="auth-label" for="reg_confirm_password">Confirm password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password"
                        class="form-control auth-input"
                        id="reg_confirm_password"
                        name="confirm_password"
                        placeholder="Retype your password"
                        required>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit">
                    Create account
                </button>
            </div>

            <p class="text-center auth-note mb-0">
                Already have an account?
                <a href="<?= base_url('login') ?>" class="auth-link">Sign in</a>
            </p>
        </form>
    </div>
</div>