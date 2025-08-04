<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Visszaküldési okok</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReturnReasonModal">
            <i class="ri ri-add-line me-2"></i>Új visszaküldési ok
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
            <h5 class="card-title mb-0">Visszaküldési okok listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($returnReasons)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-arrow-go-back-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek visszaküldési okok</p>
                        <small>Kattints az "Új visszaküldési ok" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Selejt</th>
                                <th>Jóváirható</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($returnReasons as $reason): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($reason['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($reason['is_selectable']): ?>
                                            <span class="badge bg-success">Igen</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Nem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($reason['is_creditable']): ?>
                                            <span class="badge bg-success">Igen</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Nem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteReturnReason(<?= $reason['id'] ?>, '<?= htmlspecialchars($reason['name']) ?>')">
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

<!-- Add Return Reason Modal -->
<div class="modal fade" id="addReturnReasonModal" tabindex="-1" aria-labelledby="addReturnReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReturnReasonModalLabel">Új visszaküldési ok hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="return-reasons.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Név *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_selectable" name="is_selectable" checked>
                            <label class="form-check-label" for="is_selectable">
                                Selejt (Igen/Nem)
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_creditable" name="is_creditable" checked>
                            <label class="form-check-label" for="is_creditable">
                                Jóváirható (Igen/Nem)
                            </label>
                        </div>
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
<div class="modal fade" id="deleteReturnReasonModal" tabindex="-1" aria-labelledby="deleteReturnReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteReturnReasonModalLabel">Visszaküldési ok törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteReturnReasonName"></span>" visszaküldési okot?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="return-reasons.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteReturnReasonId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteReturnReason(id, name) {
    document.getElementById('deleteReturnReasonId').value = id;
    document.getElementById('deleteReturnReasonName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteReturnReasonModal')).show();
}
</script> 