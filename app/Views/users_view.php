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

                <!-- Card: ITSO -->
                <div class="user-card" data-role="itso" data-status="active">
                    <div class="user-info">
                        <h6 class="user-name">Juan Dela Cruz</h6>
                        <p class="user-email">juan.delacruz@feutech.edu.ph</p>
                        <span class="users-tag users-tag-itso">ITSO Personnel</span>
                    </div>
                    <div class="user-actions">
                        <!-- View -->
                        <button class="users-action-btn users-action-view" data-bs-toggle="modal"
                            data-bs-target="#modalViewUser">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Edit -->
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalEditUser">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Deactivate -->
                        <button class="users-action-btn users-action-danger users-action-toggle" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmDeactivate" data-user-name="Juan Dela Cruz"
                            data-action="deactivate">
                            <i class="bi bi-power"></i>
                        </button>
                    </div>
                </div>

                <!-- Card: Student -->
                <div class="user-card" data-role="student" data-status="inactive">
                    <div class="user-info">
                        <h6 class="user-name">Maria Santos</h6>
                        <p class="user-email">maria.santos@student.feutech.edu.ph</p>
                        <span class="users-tag users-tag-student">Student</span>
                    </div>
                    <div class="user-actions">
                        <!-- View -->
                        <button class="users-action-btn users-action-view" data-bs-toggle="modal"
                            data-bs-target="#modalViewUser">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Edit -->
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalEditUser">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Activate -->
                        <button class="users-action-btn users-action-danger users-action-toggle" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmDeactivate" data-user-name="Maria Santos"
                            data-action="activate">
                            <i class="bi bi-power"></i>
                        </button>
                    </div>
                </div>

                <!-- Card: Associate -->
                <div class="user-card" data-role="associate" data-status="active">
                    <div class="user-info">
                        <h6 class="user-name">Carlos Reyes</h6>
                        <p class="user-email">carlos.reyes@associate.feutech.edu.ph</p>
                        <span class="users-tag users-tag-associate">Associate</span>
                    </div>
                    <div class="user-actions">
                        <!-- View -->
                        <button class="users-action-btn users-action-view" data-bs-toggle="modal"
                            data-bs-target="#modalViewUser">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Edit -->
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalEditUser">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Deactivate -->
                        <button class="users-action-btn users-action-danger users-action-toggle" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmDeactivate" data-user-name="Carlos Reyes"
                            data-action="deactivate">
                            <i class="bi bi-power"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="users-table-footer mt-3">
                <span class="small text-muted">Frontend demo only — no backend connection.</span>
            </div>
        </div>
    </main>

</div>

<!-- ADD USER MODAL -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content users-modal">
            <div class="modal-header users-modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body users-modal-body">
                <div class="mb-3">
                    <label class="users-label">Full Name</label>
                    <input type="text" class="form-control users-input" placeholder="Enter full name">
                </div>
                <div class="mb-3">
                    <label class="users-label">Email</label>
                    <input type="email" class="form-control users-input" placeholder="user@feutech.edu.ph">
                </div>
                <div class="mb-3">
                    <label class="users-label">Role</label>
                    <select class="form-select users-input">
                        <option selected disabled>Select role</option>
                        <option value="itso">ITSO Personnel</option>
                        <option value="associate">Associate</option>
                        <option value="student">Student</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer users-modal-footer">
                <button type="button" class="btn users-btn w-100">Save User (Frontend Only)</button>
            </div>
        </div>
    </div>
</div>

<!-- VIEW USER MODAL -->
<div class="modal fade users-modal-view" id="modalViewUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
            <div class="modal-header users-modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body users-modal-body">
                <div class="mb-3">
                    <label class="users-label">Full Name</label>
                    <input type="text" class="form-control users-input" value="Juan Dela Cruz">
                </div>
                <div class="mb-3">
                    <label class="users-label">Email</label>
                    <input type="email" class="form-control users-input" value="juan.delacruz@feutech.edu.ph">
                </div>
                <div class="mb-3">
                    <label class="users-label">Role</label>
                    <select class="form-select users-input">
                        <option value="itso" selected>ITSO Personnel</option>
                        <option value="associate">Associate</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="users-label">New Password (optional)</label>
                    <input type="password" class="form-control users-input"
                        placeholder="Enter a new password only if updating">
                </div>
                <div class="mb-3">
                    <label class="users-label">Confirm New Password</label>
                    <input type="password" class="form-control users-input" placeholder="Repeat new password">
                </div>
            </div>
            <div class="modal-footer users-modal-footer">
                <button type="button" class="btn users-btn w-100">Update User (Frontend Only)</button>
            </div>
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
                        This affects the user’s access to the ITSO EMS portal.
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
                    Keep account
                </button>
                <button type="button" class="btn users-btn users-btn-strong">
                    Yes, proceed
                </button>
            </div>
        </div>
    </div>
</div>