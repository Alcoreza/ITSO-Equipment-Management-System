<div class="auth-page">
    <div class="auth-card">
        <!-- Top badge / logo area -->
        <div class="auth-badge mb-3">
            <div class="auth-badge-icon">
                <span></span>
            </div>
            <span class="auth-badge-text">FEU Tech Access</span>
        </div>

        <h1 class="auth-title mb-1">Sign in</h1>
        <p class="auth-sub mb-3">
            Use your ITSO / Associate / Student FEU account to continue.
        </p>

        <!-- flash messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-modern alert-modern-danger mb-3">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-modern alert-modern-success mb-3">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="<?= site_url('login') ?>" novalidate>
            <!-- EMAIL -->
            <div class="mb-3">
                <label for="email" class="auth-label">Email address</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input required type="email" class="form-control auth-input" id="email" name="email"
                        placeholder="you@fit.edu.ph" autocomplete="email">
                </div>
                <div class="invalid-feedback">Please enter a valid FEU email.</div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label for="password" class="auth-label">Password</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input required type="password" class="form-control auth-input" id="password" name="password"
                        placeholder="••••••••" autocomplete="current-password">
                    <button type="button" class="btn-toggle-password" id="togglePwd" tabindex="-1"
                        aria-label="Show password">
                        Show
                    </button>
                </div>
                <div class="invalid-feedback">Please enter your password.</div>
            </div>

            <!-- REMEMBER + FORGOT -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?= site_url('password/forgot') ?>" class="auth-link-small">
                    Forgot password?
                </a>
            </div>

            <!-- PRIMARY BUTTON -->
            <div class="d-grid mb-3">
                <button class="btn auth-btn" type="submit">
                    Continue
                </button>
            </div>

            <!-- DIVIDER -->
            <div class="auth-divider my-3">
                <span>or</span>
            </div>

            <!-- SECONDARY ACTION -->
            <div class="d-grid mb-2">
                <a href="<?= site_url('register') ?>" class="btn auth-btn-outline text-center">
                    Create a new account
                </a>
            </div>

            <p class="text-center auth-note mt-3 mb-0">
                By signing in, you agree to FEU Tech’s
                <a href="#" class="auth-link">Terms</a> and
                <a href="#" class="auth-link">Privacy Policy</a>.
            </p>
        </form>
    </div>
</div>