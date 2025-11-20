<!-- app/Views/include/foot_view.php -->
<footer class="site-footer">
    <div class="container py-4">
        <div class="row align-items-center gy-3">

            <div class="col-md-4 text-center text-md-start">
                <a href="<?= site_url('/') ?>" class="d-inline-flex align-items-center text-decoration-none">
                    <img src="<?= base_url('../../public/img/indextech.avif') ?>" alt="ITSO logo"
                        style="height:40px; margin-right:10px;">
                    <span class="text-primary-strong">ITSO EMS</span>
                </a>
                <div class="text-muted small mt-2">
                    Information Technology Services Office — FEU
                </div>
            </div>

            <div class="col-md-4 text-center">
                <div class="small">
                    <strong>Contact</strong><br>
                    ITSO Office — Ground Floor, Main Building<br>
                    Email: <a href="mailto:itso@feu.edu.ph">itso@feu.edu.ph</a><br>
                    Tel: (02) 1234-5678
                </div>
            </div>

            <div class="col-md-4 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-2">
                    <a href="#" class="social-link" aria-label="Facebook" title="Facebook" target="_blank"
                        rel="noopener">
                        <!-- minimal SVG icon -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M22 12.07C22 6.48 17.52 2 12 2S2 6.48 2 12.07c0 4.99 3.66 9.12 8.44 9.93v-7.03H8.08v-2.9h2.36V9.66c0-2.33 1.38-3.62 3.5-3.62.99 0 2.03.18 2.03.18v2.23h-1.14c-1.12 0-1.47.7-1.47 1.42v1.7h2.5l-.4 2.9h-2.1V22C18.34 21.19 22 17.06 22 12.07z"
                                fill="#145C2A" />
                        </svg>
                    </a>

                    <a href="#" class="social-link" aria-label="Twitter" title="Twitter" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M22 5.92c-.64.28-1.32.48-2.04.57.73-.44 1.28-1.14 1.54-1.98-.68.4-1.44.68-2.26.84A3.48 3.48 0 0015.5 4c-1.93 0-3.5 1.63-3.5 3.64 0 .28.03.55.09.81C8.07 8.31 5.1 6.7 3 4.09c-.31.53-.49 1.15-.49 1.81 0 1.25.63 2.36 1.6 3.01-.58-.02-1.13-.18-1.61-.45v.05c0 1.74 1.19 3.19 2.78 3.52-.29.08-.6.12-.91.12-.22 0-.44-.02-.65-.06.45 1.41 1.76 2.44 3.31 2.47A7.04 7.04 0 012 19.54 9.94 9.94 0 006.29 21c6.01 0 9.31-5.1 9.31-9.52 0-.14 0-.28-.01-.42.64-.48 1.19-1.08 1.63-1.77-.58.26-1.2.44-1.85.52.66-.4 1.16-1.07 1.4-1.86z"
                                fill="#145C2A" />
                        </svg>
                    </a>

                    <a href="#" class="social-link" aria-label="Email" title="Email" target="_blank" rel="noopener">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"
                                fill="#145C2A" />
                        </svg>
                    </a>
                </div>

                <div class="mt-2 small text-muted">
                    © <?= date('Y') ?> ITSO — FEU. All rights reserved.
                </div>
            </div>

        </div>
    </div>
</footer>

<!-- include bootstrap script and site script (if any) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('public/js/main.js') ?>"></script>

</body>

</html>