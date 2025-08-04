<?php
// Ensure we have the data
if (!isset($warehouses)) {
    $warehouses = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Raktárak kezelése</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            <i class="ri ri-add-line me-2"></i>Új Raktár
        </button>
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Név</th>
                            <th>Státusz</th>
                            <th class="text-end">Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($warehouses)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="ri ri-store-line icon-3x mb-3"></i>
                                    <p>Nincsenek raktárak</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ri ri-store-line me-2 text-primary"></i>
                                            <?= htmlspecialchars($warehouse['name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($warehouse['status'] === 'active'): ?>
                                            <span class="badge bg-success">Aktív</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inaktív</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#warehouseInfoModal"
                                                    data-warehouse='<?= json_encode($warehouse) ?>'>
                                                <i class="ri ri-information-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteWarehouse(<?= $warehouse['id'] ?>)">
                                                <i class="ri ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWarehouseModalLabel">Új Raktár</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="warehouses.php?action=add" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Név *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="country" class="form-label">Ország *</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="postal_code" class="form-label">Irányítószám *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="city" class="form-label">Város *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Utca, Házszám *</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="status" class="form-label">Státusz</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active">Aktív</option>
                                <option value="inactive">Inaktív</option>
                            </select>
                        </div>
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

<!-- Warehouse Info Modal -->
<div class="modal fade" id="warehouseInfoModal" tabindex="-1" aria-labelledby="warehouseInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warehouseInfoModalLabel">Raktár Részletek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Név</label>
                        <p id="info-name" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Ország</label>
                        <p id="info-country" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">Irányítószám</label>
                        <p id="info-postal_code" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold">Város</label>
                        <p id="info-city" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Utca, Házszám</label>
                        <p id="info-address" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Státusz</label>
                        <p id="info-status" class="mb-0"></p>
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
<div class="modal fade" id="deleteWarehouseModal" tabindex="-1" aria-labelledby="deleteWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWarehouseModalLabel">Raktár Törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretné ezt a raktárat?</p>
                <p class="text-muted">Ez a művelet nem vonható vissza.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form action="warehouses.php?action=delete" method="POST" style="display: inline;">
                    <input type="hidden" id="delete-warehouse-id" name="id" value="">
                    <button type="submit" class="btn btn-danger">Törlés</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteWarehouse(id) {
    document.getElementById('delete-warehouse-id').value = id;
    new bootstrap.Modal(document.getElementById('deleteWarehouseModal')).show();
}

// Handle warehouse info modal
document.addEventListener('DOMContentLoaded', function() {
    const warehouseInfoModal = document.getElementById('warehouseInfoModal');
    if (warehouseInfoModal) {
        warehouseInfoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const warehouseData = JSON.parse(button.getAttribute('data-warehouse'));
            
            document.getElementById('info-name').textContent = warehouseData.name;
            document.getElementById('info-country').textContent = warehouseData.country;
            document.getElementById('info-postal_code').textContent = warehouseData.postal_code;
            document.getElementById('info-city').textContent = warehouseData.city;
            document.getElementById('info-address').textContent = warehouseData.address;
            
            const statusText = warehouseData.status === 'active' ? 'Aktív' : 'Inaktív';
            const statusClass = warehouseData.status === 'active' ? 'text-success' : 'text-secondary';
            document.getElementById('info-status').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
        });
    }
});
</script> 