<div class="reports-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'reports']) ?>

    <!-- MAIN CONTENT -->
    <main class="reports-main">
        <div class="reports-card">

            <!-- HEADER -->
            <div class="mb-4 text-center">
                <h1 class="reports-title">Reports Dashboard</h1>
                <p class="reports-sub">View active equipment, unusable equipment, and recent borrowing history.</p>
            </div>

            <!-- REPORT CARDS STACK -->
            <div class="reports-stack">

                <?php if ($currentPage === 1): ?>
                    <!-- Active Equipment Card (Page 1) -->
                    <div class="report-subcard">
                        <h2 class="report-subcard-title">Active Equipment</h2>
                        <?php if (!empty($activeEquipment)): ?>
                            <ul class="report-list">
                                <?php foreach ($activeEquipment as $equipment): ?>
                                    <li>
                                        <span><?= esc($equipment['equipment_name']) ?> 
                                            <?php if (!empty($equipment['equipment_type'])): ?>
                                                (<?= esc($equipment['equipment_type']) ?>)
                                            <?php endif; ?>
                                            - <?= esc($equipment['available_qty']) ?>/<?= esc($equipment['total_qty']) ?> available
                                        </span>
                                        <span class="status-badge active">Active</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No active equipment found.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($currentPage === 2): ?>
                    <!-- Unusable Equipment Card (Page 2) -->
                    <div class="report-subcard">
                        <h2 class="report-subcard-title">Unusable Equipment</h2>
                        <?php if (!empty($inactiveEquipment)): ?>
                            <ul class="report-list">
                                <?php foreach ($inactiveEquipment as $equipment): ?>
                                    <li>
                                        <span><?= esc($equipment['equipment_name']) ?>
                                            <?php if (!empty($equipment['equipment_type'])): ?>
                                                (<?= esc($equipment['equipment_type']) ?>)
                                            <?php endif; ?>
                                            - <?= esc($equipment['total_qty']) ?> total
                                        </span>
                                        <span class="status-badge unusable">
                                            Inactive
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No unusable equipment found.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($currentPage === 3): ?>
                    <!-- Recent Borrowing History Card (Page 3) -->
                    <div class="report-subcard">
                        <h2 class="report-subcard-title">Recent Borrowing History</h2>
                        <?php if (!empty($recentBorrows)): ?>
                            <ul class="report-list">
                                <?php foreach ($recentBorrows as $borrow): ?>
                                    <li>
                                        <span>
                                            <?php 
                                                // Get borrower name from users join
                                                $borrowerName = !empty($borrow['first_name']) && !empty($borrow['last_name'])
                                                    ? esc($borrow['first_name'] . ' ' . $borrow['last_name'])
                                                    : 'Unknown User';
                                            ?>
                                            <?= $borrowerName ?> - <?= esc($borrow['equipment_name'] ?? 'Unknown Equipment') ?>
                                        </span>
                                        <span class="status-badge <?= ($borrow['status'] ?? 'borrowed') === 'returned' ? 'returned' : 'borrowed' ?>">
                                            <?= esc(ucfirst($borrow['status'] ?? 'Borrowed')) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No recent borrowing history found.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- PAGINATION -->
            <div class="users-pagination-wrapper">
                <div class="users-pagination">
                    <nav aria-label="Reports pagination">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= base_url('reports?page=' . ($currentPage - 1)) ?>" <?= $currentPage <= 1 ? 'tabindex="-1"' : '' ?>>
                                    Previous
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $currentPage === $i ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('reports?page=' . $i) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= base_url('reports?page=' . ($currentPage + 1)) ?>" <?= $currentPage >= $totalPages ? 'tabindex="-1"' : '' ?>>
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </main>
</div>