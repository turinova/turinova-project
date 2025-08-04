<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Díjtípusok</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeTypeModal">
            <i class="ri ri-add-line me-2"></i>Új díjtípus
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
            <h5 class="card-title mb-0">Díjtípusok listája</h5>
        </div>
        <div class="card-body">
            <?php if (empty($feeTypes)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-money-dollar-circle-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek díjtípusok</p>
                        <small>Kattints az "Új díjtípus" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Típus</th>
                                <th>Nettó ár</th>
                                <th>ÁFA</th>
                                <th>Bruttó ár</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feeTypes as $fee): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($fee['name']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($fee['type']) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-primary"><?= number_format($fee['net_price'], 2) ?> Ft</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= htmlspecialchars($fee['vat_name']) ?> (<?= number_format($fee['vat_rate'], 2) ?>%)</span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold"><?= number_format($fee['gross_price'], 2) ?> Ft</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteFeeType(<?= $fee['id'] ?>, '<?= htmlspecialchars($fee['name']) ?>')">
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

<!-- Add Fee Type Modal -->
<div class="modal fade" id="addFeeTypeModal" tabindex="-1" aria-labelledby="addFeeTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFeeTypeModalLabel">Új díjtípus hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="fee-types.php?action=add">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Név *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Típus *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Válassz típust...</option>
                                    <option value="Díj">Díj</option>
                                    <option value="Szállítás">Szállítás</option>
                                    <option value="Kedvezmény">Kedvezmény</option>
                                    <option value="Kamat">Kamat</option>
                                    <option value="Egyéb">Egyéb</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="net_price" class="form-label">Nettó ár (Ft) *</label>
                                <input type="number" class="form-control" id="net_price" name="net_price" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vat_id" class="form-label">ÁFA *</label>
                                <select class="form-control" id="vat_id" name="vat_id" required>
                                    <option value="">Válassz ÁFA kulcsot...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gross_price" class="form-label">Bruttó ár (Ft)</label>
                                <input type="text" class="form-control" id="gross_price" readonly>
                                <div class="form-text">Automatikusan számítva</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ÁFA összeg</label>
                                <input type="text" class="form-control" id="vat_amount" readonly>
                                <div class="form-text">Automatikusan számítva</div>
                            </div>
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
<div class="modal fade" id="deleteFeeTypeModal" tabindex="-1" aria-labelledby="deleteFeeTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFeeTypeModalLabel">Díjtípus törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a "<span id="deleteFeeTypeName"></span>" díjtípust?</p>
                <p class="text-danger"><small>Ez a művelet nem vonható vissza!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form method="POST" action="fee-types.php?action=delete" style="display: inline;">
                    <input type="hidden" name="id" id="deleteFeeTypeId">
                    <button type="submit" class="btn btn-danger">
                        <i class="ri ri-delete-bin-line me-2"></i>Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Load VAT options when modal opens
document.getElementById('addFeeTypeModal').addEventListener('show.bs.modal', function () {
    loadVatOptions();
});

function loadVatOptions() {
    fetch('fee-types.php?action=get_vat_options')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const vatSelect = document.getElementById('vat_id');
                vatSelect.innerHTML = '<option value="">Válassz ÁFA kulcsot...</option>';
                
                data.data.forEach(vat => {
                    const option = document.createElement('option');
                    option.value = vat.id;
                    option.textContent = `${vat.name} (${vat.rate}%)`;
                    vatSelect.appendChild(option);
                });
                
                // Initialize Select2 after options are loaded
                if ($.fn.select2) {
                    $('#vat_id').select2({
                        placeholder: 'Válassz ÁFA kulcsot...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addFeeTypeModal')
                    });
                    
                    $('#type').select2({
                        placeholder: 'Válassz típust...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addFeeTypeModal')
                    });
                }
            }
        })
        .catch(error => console.error('Error loading VAT options:', error));
}

// Calculate gross price when net price or VAT changes
document.getElementById('net_price').addEventListener('input', calculateGrossPrice);

// Handle VAT dropdown change (works with both regular select and Select2)
document.getElementById('vat_id').addEventListener('change', calculateGrossPrice);
if ($.fn.select2) {
    $('#vat_id').on('select2:select', calculateGrossPrice);
    $('#vat_id').on('select2:unselect', calculateGrossPrice);
}

function calculateGrossPrice() {
    const netPrice = parseFloat(document.getElementById('net_price').value) || 0;
    const vatId = document.getElementById('vat_id').value;
    
    if (netPrice > 0 && vatId) {
        const formData = new FormData();
        formData.append('net_price', netPrice);
        formData.append('vat_id', vatId);
        
        fetch('fee-types.php?action=calculate_gross_price', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('gross_price').value = data.gross_price;
                const vatAmount = netPrice * (data.vat_rate / 100);
                document.getElementById('vat_amount').value = vatAmount.toFixed(2);
            }
        })
        .catch(error => console.error('Error calculating gross price:', error));
    } else {
        document.getElementById('gross_price').value = '';
        document.getElementById('vat_amount').value = '';
    }
}

function deleteFeeType(id, name) {
    document.getElementById('deleteFeeTypeId').value = id;
    document.getElementById('deleteFeeTypeName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteFeeTypeModal')).show();
}
</script> 