<?php
// Ensure we have the units data
if (!isset($units)) {
    $units = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Egységek</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
            <i class="ri ri-add-line me-2"></i>Új egység
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
            <h5 class="card-title mb-0">Egységek listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($units)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-ruler-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek egységek</p>
                        <small>Kattints az "Új egység" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Rövidítés</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($units as $unit): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($unit['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary"><?= htmlspecialchars($unit['abbreviation']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteUnit(<?= $unit['id'] ?>, '<?= htmlspecialchars($unit['name']) ?>')">
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

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUnitModalLabel">Új egység hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="units.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="pl. Darab">
                    </div>
                    <div class="mb-3">
                        <label for="abbreviation" class="form-label">Rövidítés *</label>
                        <input type="text" class="form-control" id="abbreviation" name="abbreviation" required 
                               placeholder="pl. db" maxlength="10">
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
<div class="modal fade" id="deleteUnitModal" tabindex="-1" aria-labelledby="deleteUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUnitModalLabel">Egység törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteUnitName"></span>" egységet?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="units.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteUnitId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUnit(id, name) {
    document.getElementById('deleteUnitId').value = id;
    document.getElementById('deleteUnitName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteUnitModal')).show();
}
</script> 