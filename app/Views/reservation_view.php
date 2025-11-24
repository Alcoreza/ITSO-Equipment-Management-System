<div class="reservation-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'reservation']) ?>

    <!-- MAIN CONTENT -->
    <main class="reservation-main">
        <div class="reservation-card">

            <div class="mb-4 text-center">
                <h1 class="reservation-title">Reserve Equipment</h1>
                <p class="reservation-sub">Fill out the information below to reserve equipment.</p>
            </div>

            <form id="reservationForm">

                <!-- Associate Name -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="associate_name">
                        <i class="bi bi-person-fill me-2"></i> Associate Name
                    </label>
                    <input type="text" class="reservation-input" id="associate_name" name="associate_name" placeholder="Enter name" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="email">
                        <i class="bi bi-envelope-fill me-2"></i> Email
                    </label>
                    <input type="email" class="reservation-input" id="email" name="email" placeholder="example@feutech.edu.ph" required>
                </div>

                <!-- Equipment Selection -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="equipment_id">
                        <i class="bi bi-box-fill me-2"></i> Equipment
                    </label>
                    <select class="reservation-input" id="equipment_id" name="equipment_id" required>
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

                <!-- Reservation Date -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="reserve_date">
                        <i class="bi bi-calendar-fill me-2"></i> Reservation Date
                    </label>
                    <input type="date" class="reservation-input" id="reserve_date" name="reserve_date" required>
                </div>

                <button type="submit" class="reservation-btn mt-3">Reserve Equipment</button>
            </form>
        </div>
    </main>
</div>
