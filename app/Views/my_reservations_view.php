<div class="reservation-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'reservation']) ?>

    <!-- MAIN CONTENT -->
    <main class="reservation-main">
        <div class="reservation-card">

            <div class="mb-4 text-center">
                <h1 class="reservation-title">My Reservations</h1>
                <p class="reservation-sub">Manage your equipment reservations</p>
            </div>

            <!-- flash messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-modern alert-modern-danger mb-3 text-dark" style="color:#000;">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert-modern alert-modern-success mb-3 text-dark" style="color:#000;">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <a href="<?= base_url('reservation') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Reservation
                </a>
            </div>

            <?php if (empty($reservations)): ?>
                <div class="alert-modern alert-modern-info text-dark" style="color:#000;">
                    <i class="bi bi-info-circle me-2"></i>No active reservations found for this email address.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Reservation ID</th>
                                <th>Equipment</th>
                                <th>Equipment ID</th>
                                <th>Reserved Date</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td>#<?= esc($res['id']) ?></td>
                                    <td><?= esc($res['equipment_name']) ?></td>
                                    <td>#<?= esc($res['equipment_id']) ?></td>
                                    <td><?= date('F d, Y', strtotime($res['reserve_date'])) ?></td>
                                    <td><?= !empty($res['notes']) ? esc($res['notes']) : '-' ?></td>
                                    <td>
                                        <!-- Reschedule Button -->
                                        <button type="button" class="btn btn-warning btn-sm me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rescheduleModal<?= $res['id'] ?>">
                                            <i class="bi bi-calendar-event"></i> Reschedule
                                        </button>

                                        <!-- Cancel Button -->
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#cancelModal<?= $res['id'] ?>">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </button>

                                        <!-- Reschedule Modal -->
                                        <div class="modal fade" id="rescheduleModal<?= $res['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reschedule Reservation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post" action="<?= base_url('reservation/rescheduleReservation/' . $res['id']) ?>">
                                                        <div class="modal-body">
                                                            <p><strong>Equipment:</strong> <?= esc($res['equipment_name']) ?></p>
                                                            <p><strong>Current Date:</strong> <?= date('F d, Y', strtotime($res['reserve_date'])) ?></p>
                                                            <div class="form-group mt-3">
                                                                <label for="new_date<?= $res['id'] ?>">Select New Date:</label>
                                                                <input type="date" class="form-control" 
                                                                       id="new_date<?= $res['id'] ?>" 
                                                                       name="new_date" 
                                                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                                                       required>
                                                                <small class="text-muted">Must be at least one day in advance</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-warning">Reschedule</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cancel Modal -->
                                        <div class="modal fade" id="cancelModal<?= $res['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cancel Reservation</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to cancel this reservation?</p>
                                                        <p><strong>Equipment:</strong> <?= esc($res['equipment_name']) ?></p>
                                                        <p><strong>Reserved Date:</strong> <?= date('F d, Y', strtotime($res['reserve_date'])) ?></p>
                                                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <form method="post" action="<?= base_url('reservation/cancelReservation/' . $res['id']) ?>" style="display:inline;">
                                                            <button type="submit" class="btn btn-danger">Cancel Reservation</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>