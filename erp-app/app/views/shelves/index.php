<?php
// Ensure we have the data
if (!isset($organizedData)) {
    $organizedData = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Polchelyek</h4>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                <i class="ri ri-add-line me-2"></i>Új Sor
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addColumnModal">
                <i class="ri ri-add-line me-2"></i>Új Oszlop
            </button>
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addShelfModal">
            <i class="ri ri-add-line me-2"></i>Új Polc
        </button>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <i class="ri ri-check-line me-2"></i>
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <i class="ri ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($organizedData)): ?>
            <div class="text-center py-5">
                <i class="ri ri-layout-grid-line icon-5x text-muted mb-3"></i>
                    <h5 class="text-muted">Nincsenek polchelyek</h5>
                    <p class="text-muted">Kezdje el a polchelyek létrehozását egy raktár kiválasztásával.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Raktár</th>
                                <th>Sor</th>
                                <th>Oszlop</th>
                                <th>Polc</th>
                                <th class="text-end">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizedData as $warehouse): ?>
                                <?php if (empty($warehouse['sections'])): ?>
                                    <tr class="table-light">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri ri-store-line me-2 text-primary"></i>
                                                <strong><?= htmlspecialchars($warehouse['name']) ?></strong>
                                            </div>
                                        </td>
                                        <td colspan="4" class="text-center text-muted">
                                            <em>Nincsenek sorok</em>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($warehouse['sections'] as $section): ?>
                                        <?php if (empty($section['columns'])): ?>
                                            <tr class="table-light">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri ri-store-line me-2 text-primary"></i>
                                                        <strong><?= htmlspecialchars($warehouse['name']) ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri ri-layout-grid-line me-2 text-success"></i>
                                                        <?= htmlspecialchars($section['name']) ?>
                                                    </div>
                                                </td>
                                                <td colspan="3" class="text-center text-muted">
                                                    <em>Nincsenek oszlopok</em>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($section['columns'] as $column): ?>
                                                <?php if (empty($column['shelves'])): ?>
                                                    <tr class="table-light">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri ri-store-line me-2 text-primary"></i>
                                                                <strong><?= htmlspecialchars($warehouse['name']) ?></strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri ri-layout-grid-line me-2 text-success"></i>
                                                                <?= htmlspecialchars($section['name']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri ri-layout-column-line me-2 text-warning"></i>
                                                                <?= htmlspecialchars($column['name']) ?>
                                                            </div>
                                                        </td>
                                                        <td colspan="2" class="text-center text-muted">
                                                            <em>Nincsenek polcok</em>
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($column['shelves'] as $shelf): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri ri-store-line me-2 text-primary"></i>
                                                                    <strong><?= htmlspecialchars($warehouse['name']) ?></strong>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri ri-layout-grid-line me-2 text-success"></i>
                                                                    <?= htmlspecialchars($section['name']) ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri ri-layout-column-line me-2 text-warning"></i>
                                                                    <?= htmlspecialchars($column['name']) ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="ri ri-layout-line me-2 text-info"></i>
                                                                    <?= htmlspecialchars($shelf['name']) ?>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="btn-group" role="group">
                                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#shelfInfoModal"
                                                                            data-shelf='<?= json_encode($shelf) ?>'
                                                                            data-column='<?= json_encode($column) ?>'
                                                                            data-section='<?= json_encode($section) ?>'
                                                                            data-warehouse='<?= json_encode($warehouse) ?>'>
                                                                        <i class="ri ri-information-line"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                            onclick="deleteShelf(<?= $shelf['id'] ?>)">
                                                                        <i class="ri ri-delete-bin-line"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">Új Sor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="shelves.php?action=add" method="POST">
                <input type="hidden" name="type" value="section">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warehouse_id_section" class="form-label">Raktár *</label>
                        <select class="form-select select2" id="warehouse_id_section" name="warehouse_id" required>
                            <option value="">Válasszon raktárat...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_name" class="form-label">Sor neve *</label>
                        <input type="text" class="form-control" id="section_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Column Modal -->
<div class="modal fade" id="addColumnModal" tabindex="-1" aria-labelledby="addColumnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addColumnModalLabel">Új Oszlop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="shelves.php?action=add" method="POST">
                <input type="hidden" name="type" value="column">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warehouse_id_column" class="form-label">Raktár *</label>
                        <select class="form-select select2" id="warehouse_id_column" name="warehouse_id" required>
                            <option value="">Válasszon raktárat...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_id_column" class="form-label">Sor *</label>
                        <select class="form-select select2" id="section_id_column" name="section_id" required>
                            <option value="">Először válasszon raktárat...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="column_name" class="form-label">Oszlop neve *</label>
                        <input type="text" class="form-control" id="column_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-success">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Shelf Modal -->
<div class="modal fade" id="addShelfModal" tabindex="-1" aria-labelledby="addShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addShelfModalLabel">Új Polc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="shelves.php?action=add" method="POST">
                <input type="hidden" name="type" value="shelf">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warehouse_id_shelf" class="form-label">Raktár *</label>
                        <select class="form-select select2" id="warehouse_id_shelf" name="warehouse_id" required>
                            <option value="">Válasszon raktárat...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_id_shelf" class="form-label">Sor *</label>
                        <select class="form-select select2" id="section_id_shelf" name="section_id" required>
                            <option value="">Először válasszon raktárat...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="column_id_shelf" class="form-label">Oszlop *</label>
                        <select class="form-select select2" id="column_id_shelf" name="column_id" required>
                            <option value="">Először válasszon oszlopot...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="shelf_name" class="form-label">Polc neve *</label>
                        <input type="text" class="form-control" id="shelf_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Shelf Info Modal -->
<div class="modal fade" id="shelfInfoModal" tabindex="-1" aria-labelledby="shelfInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shelfInfoModalLabel">Polc Részletek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Raktár</label>
                        <p id="info-warehouse" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Sor</label>
                        <p id="info-section" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Oszlop</label>
                        <p id="info-column" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Polc</label>
                        <p id="info-shelf" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteShelfModal" tabindex="-1" aria-labelledby="deleteShelfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteShelfModalLabel">Polc Törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretné ezt a polcot?</p>
                <p class="text-muted">Ez a művelet nem vonható vissza.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form action="shelves.php?action=delete" method="POST" style="display: inline;">
                    <input type="hidden" id="delete-shelf-id" name="id" value="">
                    <input type="hidden" name="type" value="shelf">
                    <button type="submit" class="btn btn-danger">Törlés</button>
                </form>
            </div>
        </div>
    </div>
</div> 

<script>
// Load warehouses for all modals
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded fired');
    loadWarehouses();
    
    // Initialize Select2 for Section Modal
    const addSectionModal = document.getElementById('addSectionModal');
    console.log('Section modal found:', !!addSectionModal);
    if (addSectionModal) {
        addSectionModal.addEventListener('show.bs.modal', function() {
            console.log('Section modal shown');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_section').select2({
                    placeholder: 'Válasszon raktárat...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addSectionModal')
                });
            }
        });

        addSectionModal.addEventListener('hidden.bs.modal', function() {
            console.log('Section modal hidden');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_section').select2('destroy');
            }
        });
    }
    
    // Initialize Select2 for Column Modal
    const addColumnModal = document.getElementById('addColumnModal');
    console.log('Column modal found:', !!addColumnModal);
    if (addColumnModal) {
        addColumnModal.addEventListener('show.bs.modal', function() {
            console.log('Column modal shown');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_column').select2({
                    placeholder: 'Válasszon raktárat...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addColumnModal')
                });
                window.jQuery('#section_id_column').select2({
                    placeholder: 'Válasszon sort...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addColumnModal')
                });
                
                // Set up Select2 change event for warehouse selection
                window.jQuery('#warehouse_id_column').off('select2:select').on('select2:select', function(e) {
                    console.log('Warehouse column changed (Select2):', e.params.data.id);
                    const warehouseId = e.params.data.id;
                    const sectionSelect = document.getElementById('section_id_column');
                    
                    if (warehouseId) {
                        loadSections(warehouseId, sectionSelect);
                    } else {
                        sectionSelect.innerHTML = '<option value="">Először válasszon raktárat...</option>';
                        // Update Select2 if it's initialized
                        if (window.jQuery && window.jQuery(sectionSelect).hasClass('select2-hidden-accessible')) {
                            window.jQuery(sectionSelect).trigger('change');
                        }
                    }
                });
            }
        });

        addColumnModal.addEventListener('hidden.bs.modal', function() {
            console.log('Column modal hidden');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_column').select2('destroy');
                window.jQuery('#section_id_column').select2('destroy');
            }
        });
    }
    
    // Initialize Select2 for Shelf Modal
    const addShelfModal = document.getElementById('addShelfModal');
    console.log('Shelf modal found:', !!addShelfModal);
    if (addShelfModal) {
        addShelfModal.addEventListener('show.bs.modal', function() {
            console.log('Shelf modal shown');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_shelf').select2({
                    placeholder: 'Válasszon raktárat...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addShelfModal')
                });
                window.jQuery('#section_id_shelf').select2({
                    placeholder: 'Válasszon sort...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addShelfModal')
                });
                window.jQuery('#column_id_shelf').select2({
                    placeholder: 'Válasszon oszlopot...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addShelfModal')
                });
                
                // Set up Select2 change events for cascading dropdowns
                window.jQuery('#warehouse_id_shelf').off('select2:select').on('select2:select', function(e) {
                    console.log('Warehouse shelf changed (Select2):', e.params.data.id);
                    const warehouseId = e.params.data.id;
                    const sectionSelect = document.getElementById('section_id_shelf');
                    const columnSelect = document.getElementById('column_id_shelf');
                    
                    if (warehouseId) {
                        loadSections(warehouseId, sectionSelect);
                    } else {
                        sectionSelect.innerHTML = '<option value="">Először válasszon raktárat...</option>';
                        columnSelect.innerHTML = '<option value="">Először válasszon sort...</option>';
                        // Update Select2 if they're initialized
                        if (window.jQuery && window.jQuery(sectionSelect).hasClass('select2-hidden-accessible')) {
                            window.jQuery(sectionSelect).trigger('change');
                        }
                        if (window.jQuery && window.jQuery(columnSelect).hasClass('select2-hidden-accessible')) {
                            window.jQuery(columnSelect).trigger('change');
                        }
                    }
                });
                
                window.jQuery('#section_id_shelf').off('select2:select').on('select2:select', function(e) {
                    console.log('Section shelf changed (Select2):', e.params.data.id);
                    const sectionId = e.params.data.id;
                    const columnSelect = document.getElementById('column_id_shelf');
                    
                    if (sectionId) {
                        loadColumns(sectionId, columnSelect);
                    } else {
                        columnSelect.innerHTML = '<option value="">Először válasszon sort...</option>';
                        // Update Select2 if it's initialized
                        if (window.jQuery && window.jQuery(columnSelect).hasClass('select2-hidden-accessible')) {
                            window.jQuery(columnSelect).trigger('change');
                        }
                    }
                });
            }
        });

        addShelfModal.addEventListener('hidden.bs.modal', function() {
            console.log('Shelf modal hidden');
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#warehouse_id_shelf').select2('destroy');
                window.jQuery('#section_id_shelf').select2('destroy');
                window.jQuery('#column_id_shelf').select2('destroy');
            }
        });
    }
});

function loadWarehouses() {
    console.log('Loading warehouses...');
    fetch('shelves.php?action=get_warehouses')
        .then(response => response.json())
        .then(data => {
            console.log('Warehouses response:', data);
            if (data.success) {
                const warehouses = data.data;
                const warehouseSelects = [
                    document.getElementById('warehouse_id_section'),
                    document.getElementById('warehouse_id_column'),
                    document.getElementById('warehouse_id_shelf')
                ];
                
                warehouseSelects.forEach(select => {
                    if (select) {
                        select.innerHTML = '<option value="">Válasszon raktárat...</option>';
                        warehouses.forEach(warehouse => {
                            const option = document.createElement('option');
                            option.value = warehouse.id;
                            option.textContent = warehouse.name;
                            select.appendChild(option);
                        });
                        console.log('Updated warehouse select:', select.id, 'with', warehouses.length, 'options');
                    }
                });
            }
        })
        .catch(error => console.error('Error loading warehouses:', error));
}

function loadSections(warehouseId, sectionSelect) {
    console.log('Loading sections for warehouse:', warehouseId);
    fetch(`shelves.php?action=get_sections&warehouse_id=${warehouseId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Sections response:', data);
            if (data.success) {
                // Clear existing options
                sectionSelect.innerHTML = '<option value="">Válasszon sort...</option>';
                
                // Add new options
                data.data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
                
                console.log('Updated section select with', data.data.length, 'options');
                
                // If this is a Select2 element, trigger change event to update Select2
                if (window.jQuery && window.jQuery(sectionSelect).hasClass('select2-hidden-accessible')) {
                    console.log('Updating Select2 for sections');
                    window.jQuery(sectionSelect).trigger('change');
                }
            }
        })
        .catch(error => console.error('Error loading sections:', error));
}

function loadColumns(sectionId, columnSelect) {
    console.log('Loading columns for section:', sectionId);
    fetch(`shelves.php?action=get_columns&section_id=${sectionId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Columns response:', data);
            if (data.success) {
                // Clear existing options
                columnSelect.innerHTML = '<option value="">Válasszon oszlopot...</option>';
                
                // Add new options
                data.data.forEach(column => {
                    const option = document.createElement('option');
                    option.value = column.id;
                    option.textContent = column.name;
                    columnSelect.appendChild(option);
                });
                
                console.log('Updated column select with', data.data.length, 'options');
                
                // If this is a Select2 element, trigger change event to update Select2
                if (window.jQuery && window.jQuery(columnSelect).hasClass('select2-hidden-accessible')) {
                    console.log('Updating Select2 for columns');
                    window.jQuery(columnSelect).trigger('change');
                }
            }
        })
        .catch(error => console.error('Error loading columns:', error));
}

function deleteShelf(id) {
    document.getElementById('delete-shelf-id').value = id;
    new bootstrap.Modal(document.getElementById('deleteShelfModal')).show();
}

// Handle shelf info modal
document.addEventListener('DOMContentLoaded', function() {
    const shelfInfoModal = document.getElementById('shelfInfoModal');
    if (shelfInfoModal) {
        shelfInfoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const shelfData = JSON.parse(button.getAttribute('data-shelf'));
            const columnData = JSON.parse(button.getAttribute('data-column'));
            const sectionData = JSON.parse(button.getAttribute('data-section'));
            const warehouseData = JSON.parse(button.getAttribute('data-warehouse'));
            
            document.getElementById('info-warehouse').textContent = warehouseData.name;
            document.getElementById('info-section').textContent = sectionData.name;
            document.getElementById('info-column').textContent = columnData.name;
            document.getElementById('info-shelf').textContent = shelfData.name;
        });
    }
});
</script> 