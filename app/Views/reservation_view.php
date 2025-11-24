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

            <form id="reservationForm" method="post" action="<?= base_url('reservation/submitReservation') ?>">


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
                    <label class="reservation-label" for="equipment_name">
                        <i class="bi bi-box-fill me-2"></i> Equipment
                    </label>
                    <select class="reservation-input" id="equipment_name" name="equipment_name" required>
                        <option disabled selected>Select Equipment</option>
                        <?php if (!empty($equipment_list)): ?>
                            <?php foreach ($equipment_list as $eq): ?>
                                <option value="<?= $eq['equipment_name'] ?>"><?= $eq['equipment_name'] ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>No equipment available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Reservation Date -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="reserve_date">
                        <i class="bi bi-calendar-fill me-2"></i> Reservation Date
                    </label>
                    <input type="date" class="reservation-input" id="reserve_date" name="reserve_date" required>
                </div>

                <!-- Optional Notes -->
                <div class="form-group mb-3">
                    <label class="reservation-label" for="notes">
                        <i class="bi bi-pencil-fill me-2"></i> Notes
                    </label>
                    <textarea class="reservation-input" id="notes" name="notes" placeholder="Optional notes about the reservation"></textarea>
                </div>

                <button type="submit" class="reservation-btn mt-3">Reserve Equipment</button>
            </form>
        </div>
    </main>
</div>