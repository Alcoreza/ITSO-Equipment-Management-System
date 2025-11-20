<!-- app/Views/auth/forgot.php -->

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
            Enter your FEU Tech email. We’ll send a secure reset link.
        </p>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-modern alert-modern-success mb-3">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-modern alert-modern-danger mb-3">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form id="forgotForm" method="post" action="<?= site_url('password/forgot') ?>" novalidate>
            <!-- EMAIL -->
            <div class="mb-4">
                <label for="email" class="auth-label">Email</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input required type="email" class="form-control auth-input" id="email" name="email"
                        placeholder="you@fit.edu.ph" autocomplete="email">
                </div>
                <div class="invalid-feedback">
                    Please provide your FEU Tech email address.
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit">
                    Send Reset Link
                </button>
            </div>

            <p class="text-center auth-note mb-0">
                Remembered your password?
                <a href="<?= site_url('login') ?>" class="auth-link">Sign in</a>
            </p>
        </form>

        <div class="mt-3 text-center auth-demo-note">
            Mock UI — backend will generate a secure token email.
        </div>
    </div>
</div>