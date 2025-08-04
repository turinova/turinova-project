<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Szállítási módok</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addShippingMethodModal">
            <i class="ri ri-add-line me-2"></i>Új szállítási mód
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
            <h5 class="card-title mb-0">Szállítási módok listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($shippingMethods)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-truck-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek szállítási módok</p>
                        <small>Kattints az "Új szállítási mód" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Megjegyzés</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shippingMethods as $method): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($method['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($method['description'] ?? '') ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteShippingMethod(<?= $method['id'] ?>, '<?= htmlspecialchars($method['name']) ?>')">
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

<!-- Add Shipping Method Modal -->
<div class="modal fade" id="addShippingMethodModal" tabindex="-1" aria-labelledby="addShippingMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addShippingMethodModalLabel">Új szállítási mód hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="shipping-methods.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Megjegyzés</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
<div class="modal fade" id="deleteShippingMethodModal" tabindex="-1" aria-labelledby="deleteShippingMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteShippingMethodModalLabel">Szállítási mód törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteShippingMethodName"></span>" szállítási módot?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="shipping-methods.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteShippingMethodId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteShippingMethod(id, name) {
    document.getElementById('deleteShippingMethodId').value = id;
    document.getElementById('deleteShippingMethodName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteShippingMethodModal')).show();
}
</script> 