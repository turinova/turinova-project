<?php
// Ensure we have the manufacturers data
if (!isset($manufacturers)) {
    $manufacturers = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Gyártók</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addManufacturerModal">
            <i class="ri ri-add-line me-2"></i>Új gyártó
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
        <div class="card-header">
            <h5 class="card-title mb-0">Gyártók listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($manufacturers)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-building-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek gyártók</p>
                        <small>Kattints az "Új gyártó" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Ország</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($manufacturers as $manufacturer): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($manufacturer['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info"><?= htmlspecialchars($manufacturer['country']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteManufacturer(<?= $manufacturer['id'] ?>, '<?= htmlspecialchars($manufacturer['name']) ?>')">
                                            <i class="ri ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Manufacturer Modal -->
<div class="modal fade" id="addManufacturerModal" tabindex="-1" aria-labelledby="addManufacturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManufacturerModalLabel">Új gyártó hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="manufacturers.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="pl. Samsung">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Ország *</label>
                        <input type="text" class="form-control" id="country" name="country" required 
                               placeholder="pl. Dél-Korea">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri ri-save-line me-2"></i>Mentés
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteManufacturerModal" tabindex="-1" aria-labelledby="deleteManufacturerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteManufacturerModalLabel">Gyártó törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteManufacturerName"></span>" gyártót?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="manufacturers.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteManufacturerId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteManufacturer(id, name) {
    document.getElementById('deleteManufacturerId').value = id;
    document.getElementById('deleteManufacturerName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteManufacturerModal')).show();
}
</script> 