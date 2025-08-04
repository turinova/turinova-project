<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">ÁFA kulcsok</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVatModal">
            <i class="ri ri-add-line me-2"></i>Új ÁFA kulcs
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
            <h5 class="card-title mb-0">ÁFA kulcsok listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($vatRates)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-percent-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek ÁFA kulcsok</p>
                        <small>Kattints az "Új ÁFA kulcs" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Kulcs (%)</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vatRates as $vat): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($vat['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= number_format($vat['rate'], 2) ?>%</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteVat(<?= $vat['id'] ?>, '<?= htmlspecialchars($vat['name']) ?>')">
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

<!-- Add VAT Modal -->
<div class="modal fade" id="addVatModal" tabindex="-1" aria-labelledby="addVatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVatModalLabel">Új ÁFA kulcs hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="vat.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="rate" class="form-label">Kulcs (%) *</label>
                        <input type="number" class="form-control" id="rate" name="rate" 
                               min="0" max="100" step="0.01" required>
                        <div class="form-text">Adja meg az ÁFA kulcsot százalékban (pl. 27.00)</div>
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
<div class="modal fade" id="deleteVatModal" tabindex="-1" aria-labelledby="deleteVatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVatModalLabel">ÁFA kulcs törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteVatName"></span>" ÁFA kulcsot?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="vat.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteVatId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteVat(id, name) {
    document.getElementById('deleteVatId').value = id;
    document.getElementById('deleteVatName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteVatModal')).show();
}
</script> 