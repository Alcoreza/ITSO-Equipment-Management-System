<!-- app/Views/auth/reset.php -->

<div class="auth-page">
    <div class="auth-card">
        <!-- Badge -->
        <div class="auth-badge mb-3">
            <div class="auth-badge-icon">
                <span>FT</span>
            </div>
            <span class="auth-badge-text">FEU Tech Access</span>
        </div>

        <h1 class="auth-title mb-1">Create new password</h1>
        <p class="auth-sub mb-3">
            Set a new password for your account.
            <br>
            <span class="reset-token-label">Reset token:</span>
            <span class="reset-token">
                <?= esc($token ?? '—') ?>
            </span>
        </p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-modern alert-modern-danger mb-3">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form id="resetForm" method="post" action="<?= site_url('password/reset') ?>" novalidate>
            <!-- token hidden for backend -->
            <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

            <!-- NEW PASSWORD -->
            <div class="mb-3">
                <label for="new_password" class="auth-label">New password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input required type="password" class="form-control auth-input" id="new_password"
                        name="new_password" placeholder="At least 8 characters">
                </div>
                <div class="invalid-feedback">
                    Enter a secure password (min 8 characters).
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-4">
                <label for="confirm_password" class="auth-label">Confirm password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input required type="password" class="form-control auth-input" id="confirm_password"
                        name="confirm_password" placeholder="Repeat new password">
                </div>
                <div class="invalid-feedback" id="confirmFeedback">
                    Passwords must match.
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit">
                    Update password
                </button>
            </div>

            <p class="text-center auth-note mb-0">
                Remembered your password?
                <a href="<?= site_url('login') ?>" class="auth-link">Back to login</a>
            </p>
        </form>

        <div class="mt-3 text-center auth-demo-note">
            After back-end implementation this will validate the token
            and save a hashed password.
        </div>
    </div>
</div>