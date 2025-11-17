<!-- app/Views/auth/login.php -->
<div class="container" style="padding-top:100px; padding-bottom:80px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-2" style="font-family: 'DM Sans', sans-serif;">Sign in</h3>
                    <p class="text-muted mb-3">Sign in with your ITSO / Associate / Student account.</p>

                    <!-- flash messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <form id="loginForm" method="post" action="<?= site_url('login') ?>" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input required type="email" class="form-control" id="email" name="email"
                                placeholder="you@feu.edu.ph" autocomplete="email">
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input required type="password" class="form-control" id="password" name="password"
                                    placeholder="••••••••" autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary" id="togglePwd" tabindex="-1"
                                    aria-label="Show password">Show</button>
                                <div class="invalid-feedback">Please enter your password.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember" class="small ms-1">Remember me</label>
                            </div>
                            <div>
                                <a href="<?= site_url('password/forgot') ?>" class="small">Forgot password?</a>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary btn-lg" type="submit">Login</button>
                        </div>

                        <div class="text-center text-muted small">
                            Don't have an account? <a href="<?= site_url('register') ?>">Register</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Demo help box -->
            <div class="mt-3 text-center text-muted small">
                <div>Frontend only — backend integration required for real auth.</div>
            </div>
        </div>
    </div>
</div>