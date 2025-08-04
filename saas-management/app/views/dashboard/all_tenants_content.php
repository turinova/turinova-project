<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">All Tenants 📊</h5>
            <p class="mb-4">View and manage all tenant accounts in your SaaS system.</p>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img
              src="<?= ASSETS_PATH ?>/assets/img/illustrations/man-with-laptop-light.png"
              height="140"
              alt="View Badge User"
              data-app-dark-img="illustrations/man-with-laptop-dark.png"
              data-app-light-img="illustrations/man-with-laptop-light.png" />
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

  <!-- Tenants Table -->
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Tenant List</h5>
        <div class="d-flex gap-2">
          <div class="input-group input-group-sm" style="width: 250px;">
            <input type="text" class="form-control" placeholder="Search tenants..." id="searchTenants">
            <span class="input-group-text">
              <i class="icon-base ri ri-search-line"></i>
            </span>
          </div>
          <a href="dashboard.php?action=add_tenant" class="btn btn-sm btn-primary">
            <i class="icon-base ri ri-add-line me-1"></i>
            Add New Tenant
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Identifier</th>
                <th>Company Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Plan</th>
                <th>Max Users</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($tenants)): ?>
                <?php foreach ($tenants as $tenant): ?>
                  <tr>
                    <td>
                      <span class="fw-semibold"><?php echo $tenant['id']; ?></span>
                    </td>
                    <td>
                      <span class="badge bg-label-primary"><?php echo htmlspecialchars($tenant['identifier']); ?></span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                          <span class="avatar-initial rounded bg-label-primary">
                            <?php echo strtoupper(substr($tenant['name'], 0, 1)); ?>
                          </span>
                        </div>
                        <div>
                          <h6 class="mb-0"><?php echo htmlspecialchars($tenant['name']); ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <span class="text-muted"><?php echo htmlspecialchars($tenant['email']); ?></span>
                    </td>
                    <td>
                      <?php if ($tenant['status'] === 'active'): ?>
                        <span class="badge bg-label-success">Active</span>
                      <?php elseif ($tenant['status'] === 'suspended'): ?>
                        <span class="badge bg-label-danger">Suspended</span>
                      <?php else: ?>
                        <span class="badge bg-label-warning">Inactive</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge bg-label-info"><?php echo ucfirst($tenant['plan']); ?></span>
                    </td>
                    <td>
                      <span class="text-muted"><?php echo $tenant['max_users']; ?> users</span>
                    </td>
                    <td>
                      <span class="text-muted"><?php echo date('M j, Y', strtotime($tenant['created_at'])); ?></span>
                    </td>
                    <td>
                                                   <div class="d-flex gap-2">
                               <button class="btn btn-sm btn-outline-primary" onclick="editSuperuser(<?php echo $tenant['id']; ?>, '<?php echo htmlspecialchars($tenant['identifier']); ?>', '<?php echo htmlspecialchars($tenant['name']); ?>')">
                                 <i class="icon-base ri ri-user-settings-line me-1"></i>
                                 Edit Superuser
                               </button>
                               <a href="dashboard.php?action=edit_permissions&tenant_id=<?php echo $tenant['id']; ?>" class="btn btn-sm btn-outline-info">
                                 <i class="icon-base ri ri-shield-keyhole-line me-1"></i>
                                 Edit Permissions
                               </a>
                               <div class="dropdown">
                                 <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                   <i class="icon-base ri ri-more-vertical"></i>
                                 </button>
                                 <ul class="dropdown-menu">
                                   <li><a class="dropdown-item" href="javascript:void(0);" onclick="viewTenant(<?php echo $tenant['id']; ?>)">
                                     <i class="icon-base ri ri-eye-line me-2"></i>View Details
                                   </a></li>
                                   <li><a class="dropdown-item" href="javascript:void(0);" onclick="suspendTenant(<?php echo $tenant['id']; ?>)">
                                     <i class="icon-base ri ri-pause-circle-line me-2"></i>Suspend
                                   </a></li>
                                   <li><hr class="dropdown-divider"></li>
                                   <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteTenant(<?php echo $tenant['id']; ?>)">
                                     <i class="icon-base ri ri-delete-bin-line me-2"></i>Delete
                                   </a></li>
                                 </ul>
                               </div>
                             </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="9" class="text-center py-5">
                    <div class="text-muted">
                      <i class="icon-base ri ri-inbox-line fs-1 mb-3"></i>
                      <p class="mb-0">No tenants found</p>
                      <small>Start by adding your first tenant</small>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Search functionality
document.getElementById('searchTenants').addEventListener('input', function() {
    var searchTerm = this.value.toLowerCase();
    var rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Placeholder functions for tenant actions
function editTenant(tenantId) {
    console.log('Edit tenant:', tenantId);
    // TODO: Implement general tenant edit functionality
    alert('General tenant edit functionality will be implemented later');
}

function viewTenant(tenantId) {
    console.log('View tenant:', tenantId);
    // TODO: Implement view functionality
    alert('View functionality will be implemented later');
}

function suspendTenant(tenantId) {
    console.log('Suspend tenant:', tenantId);
    // TODO: Implement suspend functionality
    alert('Suspend functionality will be implemented later');
}

function deleteTenant(tenantId) {
    console.log('Delete tenant:', tenantId);
    // TODO: Implement delete functionality
    if (confirm('Are you sure you want to delete this tenant?')) {
        alert('Delete functionality will be implemented later');
    }
}

function editSuperuser(tenantId, identifier, companyName) {
    // Set modal values
    document.getElementById('edit-tenant-id').value = tenantId;
    document.getElementById('edit-identifier').textContent = identifier;
    document.getElementById('edit-company-name').textContent = companyName;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('edit-superuser-modal'));
    modal.show();
}
</script>

<!-- Edit Superuser Modal -->
<div class="modal fade" id="edit-superuser-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="icon-base ri ri-user-settings-line me-2"></i>
          Edit Superuser Credentials
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="dashboard.php?action=edit_superuser">
        <div class="modal-body">
          <input type="hidden" id="edit-tenant-id" name="tenant_id" value="">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Tenant Identifier</label>
              <div class="form-control-plaintext" id="edit-identifier"></div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Company Name</label>
              <div class="form-control-plaintext" id="edit-company-name"></div>
            </div>
          </div>
          
          <hr class="my-4">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="new_email" class="form-label">New Email Address *</label>
              <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Enter new email address" required>
              <small class="form-text text-muted">This will be the new superuser email</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="new_password" class="form-label">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
                <span class="input-group-text cursor-pointer" onclick="toggleNewPassword()">
                  <i class="icon-base ri ri-eye-off-line"></i>
                </span>
              </div>
              <small class="form-text text-muted">Leave blank to keep the current password</small>
            </div>
          </div>
          
          <div class="alert alert-info">
            <i class="icon-base ri ri-information-line me-2"></i>
            <strong>Note:</strong> This will update the superuser credentials for the tenant's database. The changes will take effect immediately.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ri ri-save-line me-2"></i>
            Update Superuser
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleNewPassword() {
    const passwordInput = document.getElementById('new_password');
    const icon = document.querySelector('#edit-superuser-modal .input-group-text i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('ri-eye-off-line');
        icon.classList.add('ri-eye-line');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('ri-eye-line');
        icon.classList.add('ri-eye-off-line');
    }
}
</script> 