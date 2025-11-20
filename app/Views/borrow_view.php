<?= $this->include('include/head_view') ?>
<?= $this->include('include/nav_view') ?>

<div class="users-manage">

    <!-- SIDEBAR -->
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

            <a href="#" class="users-nav-item">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="users-nav-item">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>

            <a href="#" class="users-nav-item">
                <i class="bi bi-hdd-network"></i>
                <span>Equipment</span>
            </a>

            <a href="borrow" class="users-nav-item <?= uri_string() == 'borrow' ? 'is-active' : '' ?>">
                <i class="bi bi-arrow-left"></i>
                <span>Borrow</span>
            </a>

            <a href="return" class="users-nav-item <?= uri_string() == 'return' ? 'is-active' : '' ?>">
                <i class="bi bi-arrow-right"></i>
                <span>Return</span>
            </a>

            <div class="users-nav-section-label mt-3">System</div>

            <a href="#" class="users-nav-item">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="users-main borrow-main">
        <div class="borrow-card">

            <div class="mb-4">
                <h1 class="borrow-title">Borrow Equipment</h1>
                <p class="borrow-sub">Fill out the information below.</p>
            </div>

            <form id="borrowForm">

                <!-- Borrower Info -->
                <div class="form-group mb-3">
                    <label class="borrow-label">Borrower Name</label>
                    <input type="text" class="borrow-input" name="borrower_name" placeholder="Enter name" required>
                </div>

                <div class="form-group mb-3">
                    <label class="borrow-label">Email</label>
                    <input type="email" class="borrow-input" name="email" placeholder="example@feutech.edu.ph" required>
                </div>

                <!-- Equipment Selection -->
                <div class="form-group mb-3">
                    <label class="borrow-label">Equipment</label>
                    <select class="borrow-input" name="equipment_id" required>
                        <option disabled selected>Select Equipment</option>
                        <option>Laptop (with charger)</option>
                        <option>DLP Projector (with extension cord, VGA/HDMI, power cable)</option>
                        <option>HDMI Cable</option>
                        <option>VGA Cable</option>
                        <option>DLP Remote Control</option>
                        <option>Keyboard & Mouse (Mac lab, with lightning cable)</option>
                        <option>Wacom Drawing Tablet (with pen)</option>
                        <option>Speaker Set</option>
                        <option>Webcam</option>
                        <option>Extension Cord</option>
                        <option>Cable Crimping Tool</option>
                        <option>Cable Tester</option>
                        <option>Lab Room Key</option>
                    </select>
                </div>

                <!-- Expected Return Date -->
                <div class="form-group mb-3">
                    <label class="borrow-label">Expected Return Date</label>
                    <input type="date" class="borrow-input" name="return_date" required>
                </div>

                <button class="borrow-btn mt-2" type="submit">Confirm Borrow</button>

            </form>
        </div>
    </main>

</div>

<?= $this->include('include/foot_view') ?>