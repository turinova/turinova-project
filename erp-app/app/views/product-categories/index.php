<?php
// Ensure we have the data
if (!isset($mainCategories)) {
    $mainCategories = [];
}
if (!isset($subCategories)) {
    $subCategories = [];
}
if (!isset($mainCategoriesForDropdown)) {
    $mainCategoriesForDropdown = [];
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Termékkategóriák</h4>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addMainCategoryModal">
                <i class="ri ri-add-line me-2"></i>Új főkategória
            </button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                <i class="ri ri-add-line me-2"></i>Új alkategória
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

    <!-- Main Categories -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ri ri-folder-line me-2"></i>Főkategóriák
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($mainCategories)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-folder-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek főkategóriák</p>
                        <small>Kattints az "Új főkategória" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Megjegyzés</th>
                                <th>Alkategóriák</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mainCategories as $category): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($category['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($category['description'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?php
                                        $subCount = 0;
                                        foreach ($subCategories as $subCat) {
                                            if ($subCat['parent_id'] == $category['id']) {
                                                $subCount++;
                                            }
                                        }
                                        ?>
                                        <span class="badge bg-label-info"><?= $subCount ?> alkategória</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>')">
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

    <!-- Sub Categories -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ri ri-folder-open-line me-2"></i>Alkategóriák
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($subCategories)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="ri ri-folder-open-line fs-1 mb-3"></i>
                        <p class="mb-0">Nincsenek alkategóriák</p>
                        <small>Kattints az "Új alkategória" gombra a hozzáadáshoz</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Megjegyzés</th>
                                <th>Szülő kategória</th>
                                <th style="width: 100px;">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subCategories as $category): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($category['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($category['description'] ?? '') ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary"><?= htmlspecialchars($category['parent_name']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>')">
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

<!-- Add Main Category Modal -->
<div class="modal fade" id="addMainCategoryModal" tabindex="-1" aria-labelledby="addMainCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMainCategoryModalLabel">Új főkategória hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="product-categories.php?action=add" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mainCategoryName" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="mainCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mainCategoryDescription" class="form-label">Megjegyzés</label>
                        <textarea class="form-control" id="mainCategoryDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-primary">Hozzáadás</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Sub Category Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1" aria-labelledby="addSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubCategoryModalLabel">Új alkategória hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="product-categories.php?action=add" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subCategoryName" class="form-label">Megnevezés *</label>
                        <input type="text" class="form-control" id="subCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="subCategoryDescription" class="form-label">Megjegyzés</label>
                        <textarea class="form-control" id="subCategoryDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="parentCategory" class="form-label">Szülő kategória *</label>
                        <select class="form-select select2" id="parentCategory" name="parent_id" required>
                            <option value="">Válassz főkategóriát...</option>
                            <?php foreach ($mainCategoriesForDropdown as $mainCat): ?>
                                <option value="<?= $mainCat['id'] ?>"><?= htmlspecialchars($mainCat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-success">Hozzáadás</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Kategória törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Biztosan törölni szeretnéd a következő kategóriát?</p>
                <p><strong id="deleteCategoryName"></strong></p>
                <div class="alert alert-warning">
                    <i class="ri ri-alert-line me-2"></i>
                    <strong>Figyelem:</strong> A törlés nem vonható vissza!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <form action="product-categories.php?action=delete" method="POST" style="display: inline;">
                    <input type="hidden" id="deleteCategoryId" name="id">
                    <button type="submit" class="btn btn-danger">Törlés</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCategory(id, name) {
    document.getElementById('deleteCategoryId').value = id;
    document.getElementById('deleteCategoryName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
}

// Initialize Select2 for parent category dropdown when modal opens
document.addEventListener('DOMContentLoaded', function() {
    const addSubCategoryModal = document.getElementById('addSubCategoryModal');
    if (addSubCategoryModal) {
        addSubCategoryModal.addEventListener('show.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#parentCategory').select2({
                    placeholder: 'Válassz főkategóriát...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addSubCategoryModal')
                });
            }
        });

        addSubCategoryModal.addEventListener('hidden.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#parentCategory').select2('destroy');
            }
        });
    }
});
</script> 