<?php
// equipment_view.php
// Displays aggregated equipment cards (consolidated by name/type) and provides
// equipment_list for the edit-ID selector. The edit modal's select will be populated
// dynamically by main.js using the JSON-encoded equipment_list below.
?>
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
            <div class="equipment-filters mb-3 d-flex gap-2 align-items-center">
                <select class="form-select equipment-input" id="filterCategory" style="max-width:220px;">
                    <option value="">All Categories</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Drawing Tablet">Drawing Tablet</option>
                    <option value="Remote Control">Remote Control</option>
                    <option value="Projector">Projector</option>
                    <option value="Cable">Cable</option>
                    <option value="Accessory">Accessory</option>
                    <option value="Others">Others</option>
                </select>

                <select class="form-select equipment-input" id="filterStatus" style="max-width:160px;">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>

                <input id="searchInput" class="form-control equipment-input" placeholder="Search by name" style="max-width:300px;">
            </div>

            <!-- EQUIPMENT CARDS GRID (aggregated) -->
            <div class="equipment-grid">
                <?php if (!empty($equipment) && is_array($equipment)): ?>
                    <?php foreach ($equipment as $eq): ?>
                        <?php
                            // Use rep_id as representative ID (default selection when editing)
                            $repId = $eq['rep_id'] ?? ($eq['equipment_id'] ?? '');
                            $imageUrl = !empty($eq['image']) ? base_url('uploads/' . $eq['image']) : base_url('public/img/indextech.avif');
                            // aggregated quantities
                            $totalQty = $eq['total_qty'] ?? 1;
                            $availableQty = $eq['available_qty'] ?? 0;
                            $status = $eq['status'] ?? 'active';
                            $name = $eq['equipment_name'] ?? '';
                            $type = $eq['equipment_type'] ?? '';
                            $description = $eq['description'] ?? '';
                        ?>
                        <div class="equipment-card equipment-card-item <?= ($status === 'inactive') ? 'inactive-item' : '' ?>"
                             data-id="<?= htmlspecialchars($repId) ?>"
                             data-rep-id="<?= htmlspecialchars($repId) ?>"
                             data-name="<?= htmlspecialchars($name) ?>"
                             data-type="<?= htmlspecialchars($type) ?>"
                             data-status="<?= htmlspecialchars($status) ?>"
                             data-total="<?= htmlspecialchars($totalQty) ?>"
                             data-available="<?= htmlspecialchars($availableQty) ?>"
                             data-image="<?= htmlspecialchars($imageUrl) ?>"
                             data-description="<?= htmlspecialchars($description) ?>">

                            <!-- IMAGE -->
                            <img src="<?= $imageUrl ?>" class="equipment-card-img" alt="<?= htmlspecialchars($name) ?>">

                            <!-- INFO -->
                            <div class="equipment-card-info">
                                <h6 class="equipment-name"><?= htmlspecialchars($name) ?></h6>
                                <p class="equipment-meta">
                                    <?= htmlspecialchars($type) ?> • Available: <?= htmlspecialchars($availableQty) ?> / <?= htmlspecialchars($totalQty) ?>
                                </p>
                                <span class="equipment-tag"><?= htmlspecialchars($type) ?></span>
                            </div>

                            <!-- ACTIONS -->
                            <div class="equipment-actions">
                                <button class="equipment-action-btn equipment-action-view" data-bs-toggle="modal"
                                    data-bs-target="#modalViewEquipment"
                                    data-id="<?= htmlspecialchars($repId) ?>"
                                    data-name="<?= htmlspecialchars($name) ?>"
                                    data-type="<?= htmlspecialchars($type) ?>"
                                    data-status="<?= htmlspecialchars($status) ?>"
                                    data-available="<?= htmlspecialchars($availableQty) ?>"
                                    data-image="<?= htmlspecialchars($imageUrl) ?>"
                                    data-description="<?= htmlspecialchars($description) ?>">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="equipment-action-btn equipment-action-edit" data-bs-toggle="modal"
                                    data-bs-target="#modalEditEquipment"
                                    data-id="<?= htmlspecialchars($repId) ?>"
                                    data-name="<?= htmlspecialchars($name) ?>"
                                    data-type="<?= htmlspecialchars($type) ?>"
                                    data-status="<?= htmlspecialchars($status) ?>"
                                    data-available="<?= htmlspecialchars($availableQty) ?>"
                                    data-image="<?= htmlspecialchars($imageUrl) ?>"
                                    data-description="<?= htmlspecialchars($description) ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="equipment-action-btn equipment-action-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalConfirmStatus"
                                    data-id="<?= htmlspecialchars($repId) ?>"
                                    data-name="<?= htmlspecialchars($name) ?>"
                                    data-status="<?= htmlspecialchars($status) ?>">
                                    <i class="bi bi-power"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No equipment found.</p>
                <?php endif; ?>
            </div>

            <!-- ADD, VIEW, EDIT, CONFIRM MODALS (same markup as before, with edit select) -->

            <!-- ADD EQUIPMENT MODAL (frontend-only) -->
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
                            <form id="formAddEquipment" autocomplete="off">
                                <div class="mb-3">
                                    <label class="equipment-label">Item Name</label>
                                    <input id="addName" name="equipment_name" type="text" class="form-control equipment-input" placeholder="Laptop, HDMI cable..." required>
                                </div>

                                <div class="mb-3">
                                    <label class="equipment-label">Category</label>
                                    <select id="addType" name="equipment_type" class="form-select equipment-input" required>
                                        <option disabled selected>Select category</option>
                                        <option value="Laptop">Laptop</option>
                                        <option value="Drawing Tablet">Drawing Tablet</option>
                                        <option value="Remote Control">Remote Control</option>
                                        <option value="Projector">Projector</option>
                                        <option value="Cable">Cable</option>
                                        <option value="Accessory">Accessory</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <div class="mb-3 form-check">
                                    <input id="addAvailable" name="available" type="checkbox" class="form-check-input" checked>
                                    <label class="form-check-label">Available</label>
                                </div>

                                <div class="mb-3">
                                    <label class="equipment-label">Upload Image</label>
                                    <input id="addImage" name="image" type="file" class="form-control equipment-input">
                                </div>

                                <div class="mb-3">
                                    <label class="equipment-label">Description</label>
                                    <textarea id="addDescription" name="description" class="form-control equipment-input" rows="3" placeholder="Optional notes..."></textarea>
                                </div>
                            </form>
                        </div>

                        <div class="equipment-modal-footer">
                            <button id="btnAddEquipment" class="btn equipment-btn w-100">Save (Frontend Only)</button>
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
                                <img id="viewEquipmentImage" src="" class="equipment-view-img">
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
                                <p class="description-box" id="viewEquipmentDescription"></p>
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

                            <div class="equipment-view-image-wrapper mb-3">
                                <img id="editEquipmentImage" src="" class="equipment-view-img" alt="Equipment image">
                                <div class="mt-2" style="display:flex;gap:.5rem;align-items:center;">
                                    <label class="equipment-label mb-0">Replace Image</label>
                                    <input id="editImageFile" type="file" class="form-control equipment-input" style="max-width:320px;">
                                </div>
                            </div>

                            <form id="formEditEquipment" autocomplete="off">
                                <!-- Hidden field used by JS and the save action -->
                                <input type="hidden" id="editEquipmentIDHidden" name="equipment_id" value="">

                                <div class="equipment-view-info-grid">

                                    <div class="equipment-view-info-card">
                                        <label class="info-label">Item Name</label>
                                        <input id="editEquipmentName" name="equipment_name" class="form-control equipment-input info-value-input">
                                    </div>

                                    <div class="equipment-view-info-card">
                                        <label class="info-label">Category</label>
                                        <select id="editEquipmentType" name="equipment_type" class="form-select equipment-input">
                                            <option value="Laptop">Laptop</option>
                                            <option value="Drawing Tablet">Drawing Tablet</option>
                                            <option value="Remote Control">Remote Control</option>
                                            <option value="Projector">Projector</option>
                                            <option value="Cable">Cable</option>
                                            <option value="Accessory">Accessory</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="equipment-view-info-card">
                                        <label class="info-label">Status</label>
                                        <select id="editEquipmentStatus" name="status" class="form-select equipment-input">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="equipment-view-info-card">
                                        <label class="info-label">Available</label>
                                        <select id="editEquipmentAvailable" name="available" class="form-select equipment-input">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div class="equipment-view-info-card">
                                        <label class="info-label">ID</label>
                                        <!-- select to choose which specific equipment record to edit -->
                                        <select id="editEquipmentSelect" name="ID" class="form-select equipment-input">
                                            <option disabled selected>Select ID</option>
                                            <!-- options will be populated by main.js based on the equipment_name of the card -->
                                        </select>
                                    </div>

                                </div>

                                <div class="equipment-view-description mt-4">
                                    <label class="info-label mb-1">Description</label>
                                    <textarea id="editEquipmentDescription" name="description" class="form-control description-box" rows="4"></textarea>
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

            <!-- CONFIRM ACTIVATE / DEACTIVATE MODAL -->
            <div class="modal fade" id="modalConfirmStatus" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm modal-confirm-dialog">
                    <div class="modal-content equipment-modal-confirm-new">

                        <div class="confirm-topstrip"></div>

                        <div class="confirm-header d-flex align-items-center gap-3">
                            <div class="users-modal-icon-circle">
                                <i class="bi bi-power"></i>
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

                        </div>

                        <div class="confirm-footer d-flex gap-2">
                            <input type="hidden" id="confirmEquipmentID" value="">
                            <button type="button" class="btn confirm-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn confirm-btn-danger" id="confirmProceedBtn">Yes, proceed</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- make equipment_list available to main.js for dynamic select population -->
<script>
    window.EQUIPMENT_LIST = <?= isset($equipment_list) ? json_encode(array_values($equipment_list), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) : '[]' ?>;
</script>