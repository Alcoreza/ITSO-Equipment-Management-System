<main class="users-main">
<div class="auth-page">
    <div class="auth-card">
        <!-- Display Flash Messages -->
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

        <h1 class="auth-title mb-1">Account Verification</h1>
        <p class="auth-sub mb-3">
            Please check the status of your account.
        </p>
        
        <!-- Display Success or Error message -->
        <p>Your account has been <?= session()->getFlashdata('success') ? 'successfully verified!' : 'not verified. Please check the link.' ?></p>

        <!-- Optionally, add a link to login or home -->
        <a href="<?= base_url('login') ?>" class="btn btn-primary mt-3">Go to Login</a>
    </div>
</div>
</main>

