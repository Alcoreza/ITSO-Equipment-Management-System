<div class="equipment-manage users-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'equipment']) ?>

    <!-- MAIN -->
    <main class="equipment-main users-main">
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

            <!-- Filters (Search removed as requested) -->
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

                <!-- Card 1 -->
                <div class="equipment-card" data-category="Laptop" data-status="active">
                    <img src="public/img/indextech.avif" class="equipment-card-img">

                    <div class="equipment-card-info">
                        <h6 class="equipment-name">Dell Latitude 7490</h6>
                        <p class="equipment-meta">Laptop • Available: 3 / 5</p>
                        <span class="equipment-tag">Laptop</span>
                    </div>

                    <div class="equipment-actions">
                        <button class="equipment-action-btn equipment-action-view" data-bs-toggle="modal"
                            data-bs-target="#modalViewEquipment">
                            <i class="bi bi-eye"></i>
                        </button>

                        <button class="equipment-action-btn equipment-action-edit" data-bs-toggle="modal"
                            data-bs-target="#modalEditEquipment">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="equipment-action-btn equipment-action-danger" data-bs-toggle="modal"
                            data-bs-target="#modalConfirmStatus">
                            <i class="bi bi-power"></i>
                        </button>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="equipment-card" data-category="Cable" data-status="active">
                    <img src="/assets/img/equipment-placeholder.png" class="equipment-card-img">

                    <div class="equipment-card-info">
                        <h6 class="equipment-name">HDMI Cable (2m)</h6>
                        <p class="equipment-meta">Cable • Available: 10 / 12</p>
                        <span class="equipment-tag equipment-tag-cable">Cable</span>
                    </div>

                    <div class="equipment-actions">
                        <button class="equipment-action-btn equipment-action-view"><i class="bi bi-eye"></i></button>
                        <button class="equipment-action-btn equipment-action-edit"><i class="bi bi-pencil"></i></button>
                        <button class="equipment-action-btn equipment-action-danger"><i
                                class="bi bi-power"></i></button>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="equipment-card" data-category="Accessory" data-status="inactive">
                    <img src="/assets/img/equipment-placeholder.png" class="equipment-card-img">

                    <div class="equipment-card-info">
                        <h6 class="equipment-name">Wacom Tablet</h6>
                        <p class="equipment-meta text-danger">Inactive Item</p>
                        <span class="equipment-tag equipment-tag-accessory">Accessory</span>
                    </div>

                    <div class="equipment-actions">
                        <button class="equipment-action-btn equipment-action-view"><i class="bi bi-eye"></i></button>
                        <button class="equipment-action-btn equipment-action-edit"><i class="bi bi-pencil"></i></button>
                        <button class="equipment-action-btn equipment-action-danger"><i
                                class="bi bi-power"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </main>

</div>


<!-- ============================= -->
<!-- ADD EQUIPMENT MODAL          -->
<!-- ============================= -->
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
                    <input type="text" class="form-control equipment-input" placeholder="Laptop, HDMI cable...">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Category</label>
                    <select class="form-select equipment-input">
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
                    <input type="number" class="form-control equipment-input" placeholder="1">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Upload Image</label>
                    <input type="file" class="form-control equipment-input">
                </div>

                <div class="mb-3">
                    <label class="equipment-label">Description</label>
                    <textarea class="form-control equipment-input" rows="3" placeholder="Optional notes..."></textarea>
                </div>
            </div>

            <div class="equipment-modal-footer">
                <button class="btn equipment-btn w-100">Save (Frontend Only)</button>
            </div>

        </div>
    </div>
</div>




<!-- ============================= -->
<!-- PREMIUM VIEW EQUIPMENT MODAL -->
<!-- ============================= -->
<div class="modal fade" id="modalViewEquipment" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg equipment-modal-view-dialog">
        <div class="modal-content equipment-modal-view">

            <!-- HEADER -->
            <div class="equipment-modal-view-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="equipment-view-title m-0">Equipment Details</h5>
                    <small class="equipment-view-sub">Read-only information about this item</small>
                </div>

                <button type="button" class="btn-close equipment-close-light" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="equipment-modal-view-body">

                <!-- IMAGE -->
                <div class="equipment-view-image-wrapper mb-4">
                    <img src="public/img/indextech.avif" class="equipment-view-img">
                </div>

                <!-- INFO GRID -->
                <div class="equipment-view-info-grid">

                    <div class="equipment-view-info-card">
                        <p class="info-label">Item Name</p>
                        <p class="info-value">Dell Latitude 7490</p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Category</p>
                        <p class="info-value">Laptop</p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Status</p>
                        <p class="info-value text-success fw-bold">Active</p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Total Quantity</p>
                        <p class="info-value">5</p>
                    </div>

                    <div class="equipment-view-info-card">
                        <p class="info-label">Available</p>
                        <p class="info-value">3</p>
                    </div>

                </div>

                <!-- DESCRIPTION -->
                <div class="equipment-view-description mt-4">
                    <p class="info-label mb-1">Description</p>
                    <p class="description-box">
                        Placeholder description for viewing only. This modal is redesigned to be premium,
                        school-aligned, and fully visible.
                    </p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="equipment-modal-view-footer">
                <button class="btn equipment-btn px-4" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>





<!-- ============================= -->
<!-- EDIT EQUIPMENT MODAL (VIEW-STYLED) -->
<!-- ============================= -->
<div class="modal fade" id="modalEditEquipment" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg equipment-modal-view-dialog">
        <div class="modal-content equipment-modal-view equipment-modal-edit">

            <!-- HEADER -->
            <div class="equipment-modal-view-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="equipment-view-title m-0">Edit Equipment</h5>
                    <small class="equipment-view-sub">Update item information</small>
                </div>

                <button type="button" class="btn-close equipment-close-light" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="equipment-modal-view-body">

                <!-- IMAGE + replace control -->
                <div class="equipment-view-image-wrapper mb-3">
                    <img src="/mnt/data/77c15213-8412-4b3f-8a79-dd07ac4b888d.png" class="equipment-view-img"
                        alt="Equipment image">
                    <div class="mt-2" style="display:flex;gap:.5rem;align-items:center;">
                        <label class="equipment-label mb-0" style="margin-bottom:0;">Replace Image</label>
                        <input type="file" class="form-control equipment-input" style="max-width:320px;">
                    </div>
                </div>

                <!-- FORM GRID (cards become inputs) -->
                <form id="formEditEquipment" autocomplete="off">
                    <div class="equipment-view-info-grid">

                        <div class="equipment-view-info-card">
                            <label class="info-label">Item Name</label>
                            <input name="item_name" class="form-control equipment-input info-value-input"
                                value="Dell Latitude 7490">
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Category</label>
                            <select name="category" class="form-select equipment-input">
                                <option value="Laptop" selected>Laptop</option>
                                <option value="DLP">DLP</option>
                                <option value="Cable">Cable</option>
                                <option value="Accessory">Accessory</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Status</label>
                            <select name="status" class="form-select equipment-input">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Total Quantity</label>
                            <input name="total_qty" type="number" class="form-control equipment-input" value="5"
                                min="0">
                        </div>

                        <div class="equipment-view-info-card">
                            <label class="info-label">Available</label>
                            <input name="available" type="number" class="form-control equipment-input" value="3"
                                min="0">
                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="equipment-view-description mt-4">
                        <label class="info-label mb-1">Description</label>
                        <textarea name="description" class="form-control description-box"
                            rows="4">Sample description.</textarea>
                    </div>
                </form>

            </div>

            <!-- FOOTER -->
            <div class="equipment-modal-view-footer d-flex gap-2 justify-content-end">
                <button class="btn equipment-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button id="saveEditEquipment" class="btn equipment-btn equipment-btn-strong">Save changes</button>
            </div>

        </div>
    </div>
</div>




<!-- ========================================= -->
<!-- CONFIRM ACTIVATE / DEACTIVATE MODAL — REDESIGNED -->
<!-- ========================================= -->
<div class="modal fade" id="modalConfirmStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-confirm-dialog">
        <div class="modal-content equipment-modal-confirm-new">

            <!-- TOP RED STRIP (visual attention) -->
            <div class="confirm-topstrip"></div>

            <!-- HEADER -->
            <div class="confirm-header d-flex align-items-center gap-3">
                <div class="confirm-icon-wrap">
                    <!-- using local preview image as visual hint -->
                    <img src="/mnt/data/77c15213-8412-4b3f-8a79-dd07ac4b888d.png" alt="Warning"
                        class="confirm-icon-img">
                </div>

                <div class="confirm-title-wrap">
                    <h5 id="confirmActionLabel" class="confirm-title mb-0">Deactivate</h5>
                    <small class="confirm-sub">This will change the item's availability</small>
                </div>

                <button type="button" class="btn-close btn-close-dark ml-auto" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- BODY -->
            <div class="confirm-body">
                <p class="mb-2 confirm-text">
                    Are you sure you want to
                    <strong><span id="confirmActionLabelInline">deactivate</span></strong>
                    this item?
                </p>

                <p class="mb-0 confirm-item">
                    Item: <strong id="confirmEquipmentName">Dell Latitude 7490</strong>
                </p>

            </div>

            <!-- FOOTER -->
            <div class="confirm-footer d-flex gap-2">
                <button type="button" class="btn confirm-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn confirm-btn-danger" id="confirmProceedBtn">Yes, deactivate</button>
            </div>

        </div>
    </div>
</div>