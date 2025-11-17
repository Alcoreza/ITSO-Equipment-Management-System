<!-- app/Views/auth/reset.php -->
<div class="container" style="padding-top:100px; padding-bottom:120px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-2" style="font-family: 'DM Sans', sans-serif;">Create new password</h3>
                    <p class="text-muted mb-3">Set a new password for your account. Token: <span
                            class="text-monospace small"><?= esc($token ?? '—') ?></span></p>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>

                    <form id="resetForm" method="post" action="<?= site_url('password/reset') ?>" novalidate>
                        <!-- token hidden for backend -->
                        <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input required type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="At least 8 characters">
                            <div class="invalid-feedback">Enter a secure password (min 8 characters).</div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm password</label>
                            <input required type="password" class="form-control" id="confirm_password"
                                name="confirm_password" placeholder="Repeat new password">
                            <div class="invalid-feedback" id="confirmFeedback">Passwords must match.</div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary" type="submit">Update password</button>
                        </div>

                        <div class="text-center small">
                            <a href="<?= site_url('login') ?>">Back to login</a>
                        </div>
                    </form>

                </div>
            </div>

            <div class="mt-3 text-center text-muted small">
                After back-end implementation this will validate the token and save a hashed password.
            </div>
        </div>
    </div>
</div>