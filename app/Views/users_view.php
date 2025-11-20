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

            <a href="#" class="users-nav-item is-active">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>

            <a href="#" class="users-nav-item">
                <i class="bi bi-hdd-network"></i>
                <span>Equipment</span>
            </a>

            <a href="#" class="users-nav-item">
                <i class="bi bi-arrow-left-right"></i>
                <span>Borrow / Return</span>
            </a>

            <a href="#" class="users-nav-item">
                <i class="bi bi-clock-history"></i>
                <span>Logs</span>
            </a>

            <div class="users-nav-section-label mt-3">System</div>

            <a href="#" class="users-nav-item">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </nav>
    </aside>

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
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalViewUser">
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
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalViewUser">
                            <i class="bi bi-eye"></i>
                        </button>
                        <!-- Edit -->
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalEditUser">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Activate -->
                        <button class="users-action-btn users-action-success users-action-toggle" data-bs-toggle="modal"
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
                        <button class="users-action-btn" data-bs-toggle="modal" data-bs-target="#modalViewUser">
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
<div class="modal fade" id="modalViewUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content users-modal">
            <div class="modal-header users-modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body users-modal-body">
                <!-- Static demo content – backend will fill real data -->
                <div class="mb-2">
                    <div class="users-label">Full Name</div>
                    <div>Juan Dela Cruz</div>
                </div>
                <div class="mb-2">
                    <div class="users-label">Email</div>
                    <div>juan.delacruz@feutech.edu.ph</div>
                </div>
                <div class="mb-2">
                    <div class="users-label">Role</div>
                    <span class="users-tag users-tag-itso">ITSO Personnel</span>
                </div>
                <div class="mb-2">
                    <div class="users-label">Status</div>
                    <span class="badge bg-success rounded-pill px-3 py-1 small">Active</span>
                </div>
                <p class="mt-3 small text-muted mb-0">
                    Frontend mock only — connect to backend to display actual user record.
                </p>
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
                <p class="small users-modal-hint mb-0">
                    Frontend demo only — backend will perform the actual activation / deactivation.
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