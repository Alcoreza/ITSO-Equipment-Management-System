<div class="auth-page">
    <div class="auth-card">
        <!-- Badge -->
        <div class="auth-badge mb-3">
            <div class="auth-badge-icon">
                <span>FT</span>
            </div>
            <span class="auth-badge-text">FEU Tech Access</span>
        </div>

        <h1 class="auth-title mb-1">Reset Your Password</h1>
        <p class="auth-sub mb-3">
            Enter your new password below.
        </p>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-modern alert-modern-success mb-3">
                <i class="bi bi-check-circle me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-modern alert-modern-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form id="resetForm" method="post" action="<?= base_url('password/reset') ?>" novalidate>
            <?= csrf_field() ?>
            
            <!-- Hidden token field -->
            <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

            <!-- NEW PASSWORD -->
            <div class="mb-3">
                <label for="new_password" class="auth-label">New Password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input 
                        required 
                        type="password" 
                        class="form-control auth-input" 
                        id="new_password" 
                        name="new_password"
                        minlength="8"
                        placeholder="Enter new password">
                </div>
                <div class="invalid-feedback">
                    Password must be at least 8 characters.
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-4">
                <label for="confirm_password" class="auth-label">Confirm Password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input 
                        required 
                        type="password" 
                        class="form-control auth-input" 
                        id="confirm_password" 
                        name="confirm_password"
                        minlength="8"
                        placeholder="Confirm new password">
                </div>
                <div class="invalid-feedback" id="confirmFeedback">
                    Passwords must match.
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit">
                    Reset Password
                </button>
            </div>

            <p class="text-center auth-note mb-0">
                Remember your password?
                <a href="<?= base_url('login') ?>" class="auth-link">Sign in</a>
            </p>
        </form>
    </div>
</div>
