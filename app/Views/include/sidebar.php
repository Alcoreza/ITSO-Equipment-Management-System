<aside class="users-sidebar">
    <div class="users-brand mb-4">
        <div class="users-brand-mark">IT</div>
        <div class="users-brand-text">
            <div class="users-brand-title">ITSO EMS</div>
            <div class="users-brand-sub">Admin Console</div>
        </div>
    </div>

    <nav class="users-nav">
        <div class="users-nav-section-label">Navigation</div>

        <a href="<?= base_url('dashboard') ?>" class="users-nav-item <?= $active === 'dashboard' ? 'is-active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('users') ?>" class="users-nav-item <?= $active === 'users' ? 'is-active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>

        <a href="<?= base_url('equipment') ?>" class="users-nav-item <?= $active === 'equipment' ? 'is-active' : '' ?>">
            <i class="bi bi-hdd-network"></i>
            <span>Equipment</span>
        </a>

        <a href="<?= base_url('borrow') ?>" class="users-nav-item <?= $active === 'borrow' ? 'is-active' : '' ?>">
            <i class="bi bi-arrow-left"></i>
            <span>Borrow</span>
        </a>

        <a href="<?= base_url('return') ?>" class="users-nav-item <?= $active === 'return' ? 'is-active' : '' ?>">
            <i class="bi bi-arrow-right"></i>
            <span>Return</span>
        </a>

        <a href="<?= base_url('reservation') ?>" class="users-nav-item <?= $active === 'reservation' ? 'is-active' : '' ?>">
            <i class="bi bi-calendar-check"></i>
            <span>Reservation</span>
        </a>

        <a href="<?= base_url('reports') ?>" class="users-nav-item <?= $active === 'reports' ? 'is-active' : '' ?>">
            <i class="bi bi-clipboard-check"></i>
            <span>Reports</span>
        </a>

        <div class="users-nav-section-label mt-3">System</div>

        <a href="<?= base_url('settings') ?>" class="users-nav-item <?= $active === 'settings' ? 'is-active' : '' ?>">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    </nav>
</aside>