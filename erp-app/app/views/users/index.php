<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Felhasználók</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="ri ri-add-line me-2"></i>Új Felhasználó
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Felhasználónév</th>
                            <th>Email</th>
                            <th>Név</th>
                            <th>Szerep</th>
                            <th>Státusz</th>
                            <th>Utolsó bejelentkezés</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="<?= ASSETS_PATH ?>/assets/img/avatars/1.png" alt="avatar" class="rounded-circle" />
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($user['username']) ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td>
                                <span class="badge bg-label-<?= $user['role'] === 'superuser' ? 'danger' : ($user['role'] === 'admin' ? 'warning' : 'primary') ?>">
                                    <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-<?= $user['status'] === 'active' ? 'success' : ($user['status'] === 'inactive' ? 'secondary' : 'danger') ?>">
                                    <?= htmlspecialchars(ucfirst($user['status'])) ?>
                                </span>
                            </td>
                            <td><?= $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Soha' ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="ri ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editPermissionsModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['username']) ?>">
                                                <i class="ri ri-shield-user-line me-2"></i>Jogosultságok
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['username']) ?>">
                                                <i class="ri ri-lock-password-line me-2"></i>Jelszó módosítása
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['username']) ?>">
                                                <i class="ri ri-delete-bin-line me-2"></i>Törlés
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Új Felhasználó Hozzáadása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Felhasználónév *</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Keresztnév</label>
                            <input type="text" class="form-control" name="first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vezetéknév</label>
                            <input type="text" class="form-control" name="last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jelszó *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jelszó megerősítése *</label>
                            <input type="password" class="form-control" name="password_confirm" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Szerep</label>
                            <select class="form-select" name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superuser">Superuser</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Státusz</label>
                            <select class="form-select" name="status">
                                <option value="active">Aktív</option>
                                <option value="inactive">Inaktív</option>
                                <option value="suspended">Felfüggesztett</option>
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

<!-- Edit Permissions Modal -->
<div class="modal fade" id="editPermissionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Jogosultságok Szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPermissionsForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Felhasználó: <span id="permissionsUserName"></span></h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Oldal</th>
                                    <th class="text-center">Hozzáférés</th>
                                </tr>
                            </thead>
                            <tbody id="permissionsTableBody">
                                <!-- Permissions will be loaded here -->
                            </tbody>
                        </table>
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

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Jelszó Módosítása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Felhasználó: <span id="passwordUserName"></span></h6>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Új jelszó *</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Új jelszó megerősítése *</label>
                        <input type="password" class="form-control" name="new_password_confirm" required>
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Felhasználó Törlése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="ri ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center">Biztosan törölni szeretné a felhasználót: <strong id="deleteUserName"></strong>?</p>
                <p class="text-center text-muted">Ez a művelet nem vonható vissza!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Törlés</button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal event handlers
document.addEventListener('DOMContentLoaded', function() {
    let currentUserId = null;
    
    // Edit Permissions Modal
    const editPermissionsModal = document.getElementById('editPermissionsModal');
    editPermissionsModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        
        currentUserId = userId;
        document.getElementById('permissionsUserName').textContent = userName;
        
        // Load permissions for this user
        loadUserPermissions(userId);
    });
    
    // Load user permissions
    function loadUserPermissions(userId) {
        fetch(`<?= ERP_BASE_URL ?>/users-permissions.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderPermissionsTable(data.permissions);
                } else {
                    showAlert('error', data.error || 'Hiba történt a jogosultságok betöltése közben!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Hiba történt a jogosultságok betöltése közben!');
            });
    }
    
    // Render permissions table
    function renderPermissionsTable(permissions) {
        const tbody = document.getElementById('permissionsTableBody');
        tbody.innerHTML = '';
        
        permissions.forEach(page => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <i class="ri ${page.icon || 'ri-file-line'} me-2"></i>
                        ${page.title}
                    </div>
                </td>
                <td class="text-center">
                    <div class="form-check d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" name="permissions[${page.id}][can_access]" value="1" ${page.can_access ? 'checked' : ''}>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Change Password Modal
    const changePasswordModal = document.getElementById('changePasswordModal');
    changePasswordModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        
        currentUserId = userId;
        document.getElementById('passwordUserName').textContent = userName;
        
        // Clear form
        document.getElementById('changePasswordForm').reset();
    });

    // Delete User Modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    deleteUserModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        
        currentUserId = userId;
        document.getElementById('deleteUserName').textContent = userName;
    });

    // Add User Form
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('<?= ERP_BASE_URL ?>/users-add.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                document.getElementById('addUserModal').querySelector('.btn-close').click();
                this.reset();
                // Reload page to show new user
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Hiba történt a felhasználó létrehozása közben!');
        });
    });

    // Edit Permissions Form
    document.getElementById('editPermissionsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('user_id', currentUserId);
        
        fetch('<?= ERP_BASE_URL ?>/users-permissions-update.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                document.getElementById('editPermissionsModal').querySelector('.btn-close').click();
            } else {
                showAlert('error', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Hiba történt a jogosultságok mentése közben!');
        });
    });

    // Change Password Form
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('user_id', currentUserId);
        
        fetch('<?= ERP_BASE_URL ?>/users-password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                document.getElementById('changePasswordModal').querySelector('.btn-close').click();
                this.reset();
            } else {
                showAlert('error', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Hiba történt a jelszó módosítása közben!');
        });
    });

    // Delete User
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('user_id', currentUserId);
        
        fetch('<?= ERP_BASE_URL ?>/users-delete.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                document.getElementById('deleteUserModal').querySelector('.btn-close').click();
                // Reload page to remove deleted user
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Hiba történt a felhasználó törlése közben!');
        });
    });
    
    // Show alert function
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the container
        const container = document.querySelector('.container-xxl');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script> 