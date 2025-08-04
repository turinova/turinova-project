<?php /* @var $tenant array */ ?>
<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Edit Tenant Permissions 🔐</h5>
                        <p class="mb-4">Manage user permissions for <strong><?php echo htmlspecialchars($tenant['name']); ?></strong> (<?php echo htmlspecialchars($tenant['identifier']); ?>)</p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="<?= ASSETS_PATH ?>/assets/img/illustrations/auth-basic-login-mask-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" data-app-light-img="illustrations/auth-basic-login-mask-light.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="col-12 mb-4">
            <div class="alert alert-success alert-dismissible" role="alert">
                <i class="icon-base ri ri-check-line me-2"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="col-12 mb-4">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="icon-base ri ri-error-warning-line me-2"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Permissions Management -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">User Permissions</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="refreshPermissions()">
                        <i class="icon-base ri ri-refresh-line me-1"></i>
                        Refresh
                    </button>
                    <a href="dashboard.php?action=all_tenants" class="btn btn-sm btn-outline-secondary">
                        <i class="icon-base ri ri-arrow-left-line me-1"></i>
                        Back to Tenants
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div id="permissions-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading permissions...</p>
                </div>
                
                <div id="permissions-content" style="display: none;">
                    <!-- Users and their permissions will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTenantId = <?php echo $tenant['id']; ?>;
let permissionsData = null;

// Load permissions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTenantPermissions();
});

function loadTenantPermissions() {
    fetch(`dashboard.php?action=get_tenant_permissions&tenant_id=${currentTenantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                permissionsData = data;
                displayPermissions(data);
            } else {
                showError(data.error || 'Failed to load permissions');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to load permissions');
        });
}

function displayPermissions(data) {
    const contentDiv = document.getElementById('permissions-content');
    const loadingDiv = document.getElementById('permissions-loading');
    
    if (!data.users || data.users.length === 0) {
        contentDiv.innerHTML = `
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="icon-base ri ri-user-line fs-1 mb-3"></i>
                    <p class="mb-0">No users found in this tenant</p>
                    <small>Users need to be created in the tenant's ERP system</small>
                </div>
            </div>
        `;
    } else {
        let html = '';
        
        data.users.forEach(user => {
            const userPermissions = data.userPermissions[user.id] || { permissions: [] };
            const permissions = userPermissions.permissions || [];
            
            html += `
                <div class="user-permissions-section mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">${escapeHtml(user.first_name || user.username)} ${escapeHtml(user.last_name || '')}</h6>
                            <small class="text-muted">${escapeHtml(user.email)} (${escapeHtml(user.role)})</small>
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="saveUserPermissions(${user.id})">
                            <i class="icon-base ri ri-save-line me-1"></i>
                            Save Permissions
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">Access</th>
                                    <th>Page</th>
                                    <th>Route</th>
                                    <th>Icon</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${permissions.map(page => `
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="perm_${user.id}_${page.id}" 
                                                       data-user-id="${user.id}" 
                                                       data-page-id="${page.id}"
                                                       ${page.can_access ? 'checked' : ''}>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="perm_${user.id}_${page.id}" class="form-check-label">
                                                ${escapeHtml(page.title)}
                                            </label>
                                        </td>
                                        <td>
                                            <small class="text-muted">${escapeHtml(page.route)}</small>
                                        </td>
                                        <td>
                                            <i class="icon-base ri ${escapeHtml(page.icon)}"></i>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        });
        
        contentDiv.innerHTML = html;
    }
    
    loadingDiv.style.display = 'none';
    contentDiv.style.display = 'block';
}

function saveUserPermissions(userId) {
    const checkboxes = document.querySelectorAll(`input[data-user-id="${userId}"]`);
    const permissions = {};
    
    checkboxes.forEach(checkbox => {
        const pageId = checkbox.dataset.pageId;
        permissions[pageId] = {
            can_access: checkbox.checked
        };
    });
    
    const formData = new FormData();
    formData.append('tenant_id', currentTenantId);
    formData.append('user_id', userId);
    formData.append('permissions', JSON.stringify(permissions));
    
    fetch('dashboard.php?action=update_tenant_permissions', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message || 'Permissions updated successfully!');
        } else {
            showError(data.error || 'Failed to update permissions');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Failed to update permissions');
    });
}

function refreshPermissions() {
    document.getElementById('permissions-loading').style.display = 'block';
    document.getElementById('permissions-content').style.display = 'none';
    loadTenantPermissions();
}

function showSuccess(message) {
    // Create success alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible';
    alertDiv.innerHTML = `
        <i class="icon-base ri ri-check-line me-2"></i>
        ${escapeHtml(message)}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showError(message) {
    // Create error alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible';
    alertDiv.innerHTML = `
        <i class="icon-base ri ri-error-warning-line me-2"></i>
        ${escapeHtml(message)}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script> 