<div class="borrow-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'return']) ?>

    <!-- MAIN CONTENT -->
    <main class="borrow-main">
        <div class="borrow-card borrow-split">

            <!-- LEFT SIDE: FORM -->
            <div class="borrow-form-section">
                <div class="mb-2 text-center">
                    <h1 class="borrow-title">Return Equipment</h1>
                    <p class="borrow-sub">Select the equipment being returned.</p>
                </div>

                <form id="returnForm">

                    <div class="form-group mb-3">
                        <label class="borrow-label" for="borrower_name">
                            <i class="bi bi-person-fill"></i> Borrower Name
                        </label>
                        <input type="text" class="borrow-input" id="borrower_name" name="borrower_name" placeholder="Enter name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="borrow-label" for="email">
                            <i class="bi bi-envelope-fill"></i> Email
                        </label>
                        <input type="email" class="borrow-input" id="email" name="email" placeholder="example@feutech.edu.ph" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="borrow-label" for="equipment">
                            <i class="bi bi-box-seam"></i> Equipment
                        </label>
                        <select class="borrow-input" id="equipment" name="equipment" required>
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

                    <div class="form-group mb-3">
                        <label class="borrow-label" for="return_date">
                            <i class="bi bi-calendar-event-fill"></i> Return Date
                        </label>
                        <input type="date" class="borrow-input" id="return_date" name="return_date" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <button type="submit" class="borrow-btn w-100 mt-3">Confirm Return</button>
                </form>
            </div>

            <!-- RIGHT SIDE: IMAGE -->
            <div class="borrow-image-section">
                <img src="<?= base_url('/public/img/returnbg.jpg') ?>" alt="Return Illustration">
            </div>

        </div>
    </main>
</div>
