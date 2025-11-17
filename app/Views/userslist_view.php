<header class="text-center mt-5 mb-4">
    <h1 class="page-title fw-bold text-orange">User Directory</h1>
    <p class="text-muted">Manage registered users and their details</p>
</header>

<main class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= base_url('users/add'); ?>" class="btn btn-orange px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-person-plus"></i> Add New User
        </a>
    </div>

    <?php if (!empty($users)): ?>
        <div class="row g-4">
            <?php foreach ($users as $user): ?>
                <?php
                $parts = [];

                if (!empty($user['first_name'])) {
                    $parts[] = $user['first_name'];
                }

                if (!empty($user['middle_name'])) {
                    $parts[] = substr($user['middle_name'], 0, 1) . '.';
                }

                if (!empty($user['last_name'])) {
                    $parts[] = $user['last_name'];
                }

                $fullname = implode(' ', $parts);

                if (!empty($user['suffix'])) {
                    $fullname .= ', ' . $user['suffix'];
                }
                ?>

                <div class="col-md-6 col-lg-4">
                    <div class="user-card p-4 shadow-sm rounded-4 bg-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar me-3">
                                <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--orange-color);"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold"><?= esc($fullname) ?></h5>
                                <small class="text-muted">@<?= esc($user['username']) ?></small>
                            </div>
                        </div>

                        <p class="mb-2 text-muted">
                            <i class="bi bi-envelope-fill me-2 text-orange"></i><?= esc($user['email']) ?>
                        </p>

                        <?php if (!empty($user['role'])): ?>
                            <p class="mb-3">
                                <span class="badge bg-light text-orange border border-orange px-3 py-2">
                                    <?= ucfirst(esc($user['role'])) ?>
                                </span>
                            </p>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('users/view/' . $user['id']) ?>"
                                class="btn btn-sm btn-outline-success rounded-pill px-3">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="<?= base_url('users/edit/' . $user['id']) ?>"
                                class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-btn"
                                data-url="<?= base_url('users/delete/' . $user['id']) ?>">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted mt-5">No users found.</p>
    <?php endif; ?>
</main>