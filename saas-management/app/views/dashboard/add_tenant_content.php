<div class="row">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Add New Tenant 🚀</h5>
                                <p class="mb-4">Create a new tenant account for the ERP system</p>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="<?= ASSETS_PATH ?>/assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
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

            <!-- Add Tenant Form -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tenant Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="dashboard.php?action=add_tenant">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="identifier" class="form-label">Tenant Identifier *</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="identifier"
                                        name="identifier"
                                        placeholder="Enter tenant identifier"
                                        required />
                                    <small class="form-text text-muted">Only letters, numbers and hyphens (e.g: company1)</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Company Name *</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        name="name"
                                        placeholder="Enter company name"
                                        required />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        placeholder="Enter email address"
                                        required />
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            placeholder="Enter password"
                                            required />
                                        <span class="input-group-text cursor-pointer" onclick="togglePassword()">
                                            <i class="icon-base ri ri-eye-off-line"></i>
                                        </span>
                                    </div>
                                    <small class="form-text text-muted">Minimum 6 characters</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="plan" class="form-label">Plan</label>
                                    <select class="form-control" id="plan" name="plan">
                                        <option value="basic">Basic Plan</option>
                                        <option value="premium">Premium Plan</option>
                                        <option value="enterprise">Enterprise Plan</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="max_users" class="form-label">Maximum Users</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="max_users"
                                        name="max_users"
                                        placeholder="Enter maximum users"
                                        value="10"
                                        min="1"
                                        max="1000" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="icon-base ri ri-add-line me-1"></i>
                                        Create Tenant
                                    </button>
                                    <a href="dashboard.php" class="btn btn-outline-secondary ms-2">
                                        <i class="icon-base ri ri-arrow-left-line me-1"></i>
                                        Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('.input-group-text i');
    
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