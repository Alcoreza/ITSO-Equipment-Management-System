<!-- app/Views/auth/forgot.php -->
<div class="container" style="padding-top:100px; padding-bottom:80px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-2" style="font-family: 'DM Sans', sans-serif;">Reset password</h3>
                    <p class="text-muted mb-3">Enter the email associated with your account. We'll send instructions to
                        reset your password.</p>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <form id="forgotForm" method="post" action="<?= site_url('password/forgot') ?>" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input required type="email" class="form-control" id="email" name="email"
                                placeholder="you@feu.edu.ph">
                            <div class="invalid-feedback">Please provide your email address.</div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary" type="submit">Send reset link</button>
                        </div>

                        <div class="text-center small">
                            Remembered your password? <a href="<?= site_url('login') ?>">Sign in</a>
                        </div>
                    </form>

                </div>
            </div>

            <div class="mt-3 text-center text-muted small">
                This is a front-end mock. Back-end should generate and email a secure reset link with a token.
            </div>
        </div>
    </div>
</div>