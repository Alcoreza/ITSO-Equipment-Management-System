<div class="users-manage">

    <?= view('include/sidebar', ['active' => 'users']) ?>

    <!-- MAIN CONTENT -->
    <main class="users-main">
        <div class="users-card">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                <div>
                    <h1 class="users-title mb-1">User Management</h1>
                    <p class="users-sub mb-0">
                        Manage ITSO Personnel, Associates and Students.
                    </p>
                </div>

                <button class="btn users-btn" data-bs-toggle="modal" data-bs-target="#modalAddUser">
                    <i class="bi bi-plus-lg me-1"></i> Add User
                </button>
            </div>

            <!-- FILTERS -->
            <div class="users-filters mb-3">
                <div class="users-filter-group">
                    <select id="filterRole" class="form-select users-input">
                        <option value="">All Roles</option>
                        <option value="itso">ITSO Personnel</option>
                        <option value="associate">Associate</option>
                        <option value="student">Student</option>
                    </select>

                    <select id="filterStatus" class="form-select users-input">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- USER CARDS GRID -->
            <div class="users-grid">

    <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>

            <div class="user-card"
                data-id="<?= $u['id'] ?>"
                data-role="<?= esc($u['role']) ?>"
                data-status="<?= $u['status'] == 1 ? 'active' : 'inactive' ?>">

                <div class="user-info">
                    <h6 class="user-name">
                        <?= esc($u['first_name'] . ' ' . $u['last_name']) ?>
                    </h6>

                    <p class="user-email"><?= esc($u['email']) ?></p>

                    <?php
                        // Role Badges
                        $tagClass = 'users-tag-student';
                        if ($u['role'] === 'itso') $tagClass = 'users-tag-itso';
                        elseif ($u['role'] === 'associate') $tagClass = 'users-tag-associate';
                    ?>
                    <span class="users-tag <?= $tagClass ?>">
                        <?= ucfirst($u['role']) ?>
                    </span>

                </div>

                <div class="user-actions">
                   <button class="users-action-btn users-action-view"
                            data-bs-toggle="modal"
                            data-bs-target="#modalViewUser"
                            data-id="<?= $u['id'] ?>">
                        <i class="bi bi-eye"></i>
                    </button>
                    
                    <button class="users-action-btn btn-edit-user"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditUser"
                        data-id="<?= $u['id'] ?>"
                        data-first="<?= esc($u['first_name']) ?>"
                        data-last="<?= esc($u['last_name']) ?>"
                        data-email="<?= esc($u['email']) ?>"
                        data-role="<?= esc($u['role']) ?>">
                        <i class="bi bi-pencil"></i>
                    </button>



                    <button class="users-action-btn users-action-danger users-action-toggle"
                            data-bs-toggle="modal"
                            data-bs-target="#modalConfirmDeactivate"
                            data-id="<?= $u['id'] ?>"
                            data-user-name="<?= esc($u['first_name'] . ' ' . $u['last_name']) ?>"
                            data-action="<?= $u['status'] == 1 ? 'deactivate' : 'activate' ?>">
                        <i class="bi bi-power"></i>
                    </button>
                </div>

            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</div>
            <!-- PAGINATION -->
                <div class="users-pagination-wrapper">
                    <div class="users-pagination">
                        <?php if (isset($pager) && $pager): ?>
                            <?= $pager->links('default', 'default_full') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content users-modal">
            <form id="addUserForm" action="<?= base_url('admin/addUser') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="modal-header users-modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                
                <div class="modal-body users-modal-body">
                    <!-- Flash messages inside modal -->
                    <div id="addUserAlert" style="display: none;" class="alert mb-3"></div>
                    
                    <div class="mb-3">
                        <label class="users-label">Full Name</label>
                        <input type="text" 
                               class="form-control users-input" 
                               name="fullname" 
                               id="addFullname"
                               placeholder="Enter full name" 
                               required>
                        <div class="invalid-feedback">Please enter full name.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="users-label">Email</label>
                        <input type="email" 
                               class="form-control users-input" 
                               name="email" 
                               id="addEmail"
                               placeholder="user@feutech.edu.ph" 
                               required>
                        <div class="invalid-feedback">Enter a valid email address.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="users-label">Role</label>
                        <select class="form-select users-input" name="role" id="addRole" required>
                            <option value="" selected disabled>Select role</option>
                            <option value="itso">ITSO Personnel</option>
                            <option value="associate">Associate</option>
                            <option value="student">Student</option>
                        </select>
                        <div class="invalid-feedback">Please select a role.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="users-label">Password</label>
                        <input type="password" 
                               class="form-control users-input" 
                               name="password" 
                               id="addPassword"
                               minlength="8"
                               placeholder="Create a password" 
                               required>
                        <div class="invalid-feedback">Password must be at least 8 characters.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="users-label">Confirm Password</label>
                        <input type="password" 
                               class="form-control users-input" 
                               name="confirm_password" 
                               id="addConfirmPassword"
                               minlength="8"
                               placeholder="Retype password" 
                               required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>
                </div>
                
                <div class="modal-footer users-modal-footer">
                    <button type="submit" class="btn users-btn w-100">
                        <span class="btn-text">Create User</span>
                        <span class="btn-spinner" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Creating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- VIEW USER MODAL -->
<div class="modal fade users-modal-view" id="modalViewUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p><strong>Full Name:</strong> <span id="viewUserName">Juan Dela Cruz</span></p>
                <p><strong>Email:</strong> <span id="viewUserEmail">juan.delacruz@feutech.edu.ph</span></p>
                <p>
                    <strong>Role:</strong>
                    <span class="users-modal-role" id="viewUserRole">ITSO Personnel</span>
                </p>
                <p><strong>Status:</strong> <span id="viewUserStatus">Active</span></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn users-btn" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content users-modal">

            <form action="<?= base_url('admin/updateUser') ?>" method="post">

                <div class="modal-header users-modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body users-modal-body">

                    <input type="hidden" name="id" id="editUserId">

                    <div class="mb-3">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" id="editFirstName" required>
                    </div>

                    <div class="mb-3">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="editLastName" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="editEmail" required>
                    </div>

                    <div class="mb-3">
                        <label>Role</label>
                        <select class="form-select" name="role" id="editRole" required>
                            <option value="itso">ITSO Personnel</option>
                            <option value="associate">Associate</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>New Password (optional)</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="mb-3">
                        <label>Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirm">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CONFIRM ACTIVATE / DEACTIVATE MODAL -->
<div class="modal fade" id="modalConfirmDeactivate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content users-modal users-modal-confirm">
            <div class="users-modal-confirm-top"></div>

            <div class="modal-header users-modal-header users-modal-confirm-header">
                <div class="users-modal-icon-wrap">
                    <div class="users-modal-icon-circle">
                        <i class="bi bi-power"></i>
                    </div>
                </div>
                <div>
                    <h5 class="modal-title mb-0">
                        <span id="confirmActionLabel">Deactivate</span> user
                    </h5>
                    <small class="users-modal-subtitle">
                        This affects the user’s access.
                    </small>
                </div>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body users-modal-body users-modal-confirm-body">
                <p class="mb-2">
                    Are you sure you want to
                    <strong><span id="confirmActionLabelInline">deactivate</span></strong>
                    this account?
                </p>
                <p class="mb-2">
                    User: <strong id="confirmUserName">Juan Dela Cruz</strong>
                </p>
            </div>

            <div class="modal-footer users-modal-footer users-modal-confirm-footer">
                <button type="button" class="btn users-btn-ghost" data-bs-dismiss="modal">
                    Cancel
                </button>

                <!-- Form to submit activation/deactivation -->
                <form id="confirmToggleForm" method="post" action="<?= base_url('admin/toggleUser') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="confirmToggleId" value="">
                    <input type="hidden" name="action" id="confirmToggleAction" value="deactivate">
                    <button type="submit" class="btn users-btn users-btn-strong">
                        Yes, proceed
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>