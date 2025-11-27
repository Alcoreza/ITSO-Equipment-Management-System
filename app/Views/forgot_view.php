<div class="auth-page">
    <div class="auth-card">
        <!-- Badge -->
        <div class="auth-badge mb-3">
            <div class="auth-badge-icon">
                <span>FT</span>
            </div>
            <span class="auth-badge-text">FEU Tech Access</span>
        </div>

        <h1 class="auth-title mb-1">Forgot Password?</h1>
        <p class="auth-sub mb-3">
            Enter your email address and we'll send you instructions to reset your password.
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

        <form id="forgotForm" method="post" action="<?= base_url('password/forgot') ?>" novalidate>
            <?= csrf_field() ?>

            <!-- EMAIL -->
            <div class="mb-4">
                <label for="forgot_email" class="auth-label">Email address</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input 
                        required 
                        type="email" 
                        class="form-control auth-input" 
                        id="forgot_email" 
                        name="email"
                        value="<?= old('email') ?>"
                        placeholder="you@fit.edu.ph" 
                        autocomplete="email">
                </div>
                <div class="invalid-feedback">
                    Please enter a valid FEU Tech email address.
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit" id="btnSendReset">
                    <span class="btn-text">Send Reset Instructions</span>
                    <span class="btn-spinner" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Sending...
                    </span>
                </button>
            </div>

            <p class="text-center auth-note mb-0">
                Remember your password?
                <a href="<?= base_url('login') ?>" class="auth-link">Sign in</a>
            </p>
        </form>
    </div>
</div>