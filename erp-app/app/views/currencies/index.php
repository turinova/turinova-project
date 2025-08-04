<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Pénznemek</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCurrencyModal">
            <i class="ri ri-add-line me-2"></i>Új pénznem
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
            <h5 class="card-title mb-0">Pénznemek listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($currencies)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-money-dollar-circle-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek pénznemek</p>
                        <small>Kattints az "Új pénznem" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Pénznem</th>
                                <th>Átváltási ráta</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($currencies as $currency): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($currency['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= number_format($currency['exchange_rate'], 4) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteCurrency(<?= $currency['id'] ?>, '<?= htmlspecialchars($currency['name']) ?>')">
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

<!-- Add Currency Modal -->
<div class="modal fade" id="addCurrencyModal" tabindex="-1" aria-labelledby="addCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCurrencyModalLabel">Új pénznem hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="currencies.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Pénznem *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Átváltási ráta *</label>
                        <input type="number" class="form-control" id="exchange_rate" name="exchange_rate" 
                               min="0.0001" max="999999.9999" step="0.0001" required>
                        <div class="form-text">Adja meg az átváltási rátát HUF-hoz képest (pl. 349.25)</div>
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
<div class="modal fade" id="deleteCurrencyModal" tabindex="-1" aria-labelledby="deleteCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCurrencyModalLabel">Pénznem törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteCurrencyName"></span>" pénznemet?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="currencies.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteCurrencyId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCurrency(id, name) {
    document.getElementById('deleteCurrencyId').value = id;
    document.getElementById('deleteCurrencyName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteCurrencyModal')).show();
}
</script> 