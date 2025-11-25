<div class="equipment-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'equipment']) ?>

    <!-- MAIN -->
    <main class="equipment-main">
        <div class="equipment-card-wrapper">

            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                <div>
                    <h1 class="equipment-title mb-1">Equipment Management</h1>
                    <p class="equipment-sub mb-0">
                        Manage laptops, cables, DLP projectors, lab keys, and more.
                    </p>
                </div>

                <button class="btn equipment-btn" data-bs-toggle="modal" data-bs-target="#modalAddEquipment">
                    <i class="bi bi-plus-lg me-1"></i> Add Equipment
                </button>
            </div>

            <!-- Filters -->
            <div class="equipment-filters mb-3">
                <select class="form-select equipment-input" id="filterCategory">
                    <option value="">All Categories</option>
                    <option value="Laptop">Laptop</option>
                    <option value="DLP">DLP</option>
                    <option value="Cable">Cable</option>
                    <option value="Accessory">Accessory</option>
                    <option value="Others">Others</option>
                </select>

                <select class="form-select equipment-input" id="filterStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- EQUIPMENT CARDS GRID -->
            <div class="equipment-grid">
                <?php foreach ($equipment as $eq): ?>
                    <div class="equipment-card" 
                         data-category="<?= htmlspecialchars($eq['equipment_type']) ?>" 
                         data-status="<?= htmlspecialchars($eq['status']) ?>"
                         data-id="<?= $eq['equipment_id'] ?>"
                         data-name="<?= htmlspecialchars($eq['equipment_name']) ?>"
                         data-available="<?= $eq['available'] ?>"
                         data-status-text="<?= $eq['status'] ?>"
                         data-type="<?= htmlspecialchars($eq['equipment_type']) ?>">
                        
                        <img src="<?= isset($eq['image']) ? $eq['image'] : '/assets/img/equipment-placeholder.png' ?>" class="equipment-card-img">

                        <div class="equipment-card-info">
                            <h6 class="equipment-name"><?= htmlspecialchars($eq['equipment_name']) ?></h6>
                            <p class="equipment-meta <?= $eq['status'] === 'inactive' ? 'text-danger' : '' ?>">
                                <?= htmlspecialchars($eq['equipment_type']) ?> • Available: <?= $eq['available'] ?>
                            </p>
                            <span class="equipment-tag"><?= htmlspecialchars($eq['equipment_type']) ?></span>
                        </div>

                        <div class="equipment-actions">
                            <button class="equipment-action-btn equipment-action-view" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalViewEquipment"
                                    onclick="populateViewModal(this)">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button class="equipment-action-btn equipment-action-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditEquipment"
                                    onclick="populateEditModal(this)">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button class="equipment-action-btn equipment-action-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalConfirmStatus"
                                    onclick="populateConfirmModal(this)">
                                <i class="bi bi-power"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>
</div>

<!-- ADD EQUIPMENT MODAL -->
<div class="modal fade" id="modalAddEquipment" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content equipment-modal">

            <div class="equipment-modal-header">
                <div class="equipment-modal-icon-circle">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <h5 class="modal-title">Add Equipment</h5>
                <button type="button" class="btn-close equipment-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="equipment-modal-body">
                <div class="mb-3">
                    <label class="equipment-label">Item Name</label>
                    <input type="text" name="equipment_name" class="form-control equipment-input" placeholder="Laptop, HDMI cable...">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Category</label>
                    <select name="equipment_type" class="form-select equipment-input">
                        <option disabled selected>Select category</option>
                        <option>Laptop</option>
                        <option>DLP</option>
                        <option>Cable</option>
                        <option>Accessory</option>
                        <option>Others</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Total Quantity</label>
                    <input type="number" name="available" class="form-control equipment-input" placeholder="1" min="0">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Upload Image</label>
                    <input type="file" name="image" class="form-control equipment-input">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Description</label>
                    <textarea name="description" class="form-control equipment-input" rows="3" placeholder="Optional notes..."></textarea>
                </div>
            </div>

            <div class="equipment-modal-footer">
                <button class="btn equipment-btn w-100">Save (Frontend Only)</button>
            </div>

        </div>
    </div>
</div>

<!-- VIEW EQUIPMENT MODAL -->
<div class="modal fade" id="modalViewEquipment" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg equipment-modal-view-dialog">
        <div class="modal-content equipment-modal-view">

            <div class="equipment-modal-view-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="equipment-view-title m-0">Equipment Details</h5>
                    <small class="equipment-view-sub">Read-only information about this item</small>
                </div>
                <button type="button" class="btn-close equipment-close-light" data-bs-dismiss="modal"></button>
            </div>

            <div class="equipment-modal-view-body">
                <div class="equipment-view-image-wrapper mb-4">
                    <img src="" class="equipment-view-img" id="viewEquipmentImage">
                </div>

                <div class="equipment-view-info-grid">
                    <div class="equipment-view-info-card">
                        <p class="info-label">Item Name</p>
                        <p class="info-value" id="viewEquipmentName"></p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Category</p>
                        <p class="info-value" id="viewEquipmentType"></p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Status</p>
                        <p class="info-value" id="viewEquipmentStatus"></p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Available</p>
                        <p class="info-value" id="viewEquipmentAvailable"></p>
                    </div>
                </div>

                <div class="equipment-view-description mt-4">
                    <p class="info-label mb-1">Description</p>
                    <p class="description-box" id="viewEquipmentDescription">No description available.</p>
                </div>
            </div>

            <div class="equipment-modal-view-footer">
                <button class="btn equipment-btn px-4" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- EDIT EQUIPMENT MODAL -->
<div class="modal fade" id="modalEditEquipment" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg equipment-modal-view-dialog">
        <div class="modal-content equipment-modal-view equipment-modal-edit">

            <div class="equipment-modal-view-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="equipment-view-title m-0">Edit Equipment</h5>
                    <small class="equipment-view-sub">Update item information</small>
                </div>
                <button type="button" class="btn-close equipment-close-light" data-bs-dismiss="modal"></button>
            </div>

            <div class="equipment-modal-view-body">
                <form id="formEditEquipment" autocomplete="off">
                    <div class="equipment-view-image-wrapper mb-3">
                        <img src="" class="equipment-view-img" id="editEquipmentImage">
                        <div class="mt-2" style="display:flex;gap:.5rem;align-items:center;">
                            <label class="equipment-label mb-0">Replace Image</label>
                            <input type="file" name="image" class="form-control equipment-input" style="max-width:320px;">
                        </div>
                    </div>

                    <div class="equipment-view-info-grid">
                        <div class="equipment-view-info-card">
                            <label class="info-label">Item Name</label>
                            <input name="equipment_name" class="form-control equipment-input info-value-input" id="editEquipmentName">
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Category</label>
                            <select name="equipment_type" class="form-select equipment-input" id="editEquipmentType">
                                <option value="Laptop">Laptop</option>
                                <option value="DLP">DLP</option>
                                <option value="Cable">Cable</option>
                                <option value="Accessory">Accessory</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Status</label>
                            <select name="status" class="form-select equipment-input" id="editEquipmentStatus">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Available</label>
                            <input name="available" type="number" class="form-control equipment-input" id="editEquipmentAvailable" min="0">
                        </div>

                        <input type="hidden" name="equipment_id" id="editEquipmentID">
                    </div>

                    <div class="equipment-view-description mt-4">
                        <label class="info-label mb-1">Description</label>
                        <textarea name="description" class="form-control description-box" rows="4" id="editEquipmentDescription"></textarea>
                    </div>
                </form>
            </div>

            <div class="equipment-modal-view-footer d-flex gap-2 justify-content-end">
                <button class="btn equipment-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button id="saveEditEquipment" class="btn equipment-btn equipment-btn-strong">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- CONFIRM STATUS MODAL -->
<div class="modal fade" id="modalConfirmStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-confirm-dialog">
        <div class="modal-content equipment-modal-confirm-new">

            <div class="confirm-topstrip"></div>

            <div class="confirm-header d-flex align-items-center gap-3">
                <div class="confirm-icon-wrap">
                    <img src="/assets/img/equipment-placeholder.png" alt="Warning" class="confirm-icon-img">
                </div>

                <div class="confirm-title-wrap">
                    <h5 id="confirmActionLabel" class="confirm-title mb-0">Deactivate</h5>
                    <small class="confirm-sub">This will change the item's availability</small>
                </div>

                <button type="button" class="btn-close btn-close-dark ml-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="confirm-body">
                <p class="mb-2 confirm-text">
                    Are you sure you want to
                    <strong><span id="confirmActionLabelInline">deactivate</span></strong>
                    this item?
                </p>

                <p class="mb-0 confirm-item">
                    Item: <strong id="confirmEquipmentName"></strong>
                </p>

                <input type="hidden" id="confirmEquipmentID">
            </div>

            <div class="confirm-footer d-flex gap-2">
                <button type="button" class="btn confirm-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn confirm-btn-danger" id="confirmProceedBtn">Yes, proceed</button>
            </div>
        </div>
    </div>
</div>