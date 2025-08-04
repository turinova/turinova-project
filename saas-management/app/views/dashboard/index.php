<div class="row">
              <div class="col-lg-12 mb-4 order-0">
              <div class="card">
                <div class="d-flex align-items-end row">
                  <div class="col-sm-7">
                    <div class="card-body">
                      <h5 class="card-title text-primary">SaaS Management Dashboard 🎉</h5>
                      <p class="mb-4">Manage your multi-tenant ERP system. Monitor tenants, track usage, and maintain system health.</p>
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

  <!-- Statistics Cards -->
  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
              src="<?= ASSETS_PATH ?>/assets/img/icons/unicons/chart-success.png"
              alt="chart success"
              class="rounded" />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt1"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt1">
              <a class="dropdown-item" href="javascript:void(0);">View All</a>
              <a class="dropdown-item" href="javascript:void(0);">Export</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Tenants</span>
        <h3 class="card-title mb-2"><?php echo $totalTenants ?? 0; ?></h3>
        <small class="text-success fw-semibold">
          <i class="icon-base ri ri-arrow-up-line"></i> +12%
        </small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img
              src="<?= ASSETS_PATH ?>/assets/img/icons/unicons/wallet-info.png"
              alt="Credit Card"
              class="rounded" />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt2"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt2">
              <a class="dropdown-item" href="javascript:void(0);">View All</a>
              <a class="dropdown-item" href="javascript:void(0);">Export</a>
            </div>
          </div>
        </div>
        <span>Active Tenants</span>
        <h3 class="card-title text-nowrap mb-1"><?php echo $activeTenants ?? 0; ?></h3>
        <small class="text-success fw-semibold">
          <i class="icon-base ri ri-arrow-up-line"></i> +28.14%
        </small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img src="<?= ASSETS_PATH ?>/assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
              <a class="dropdown-item" href="javascript:void(0);">View All</a>
              <a class="dropdown-item" href="javascript:void(0);">Export</a>
            </div>
          </div>
        </div>
        <span>Suspended</span>
        <h3 class="card-title text-nowrap mb-1"><?php echo $suspendedTenants ?? 0; ?></h3>
        <small class="text-danger fw-semibold">
          <i class="icon-base ri ri-arrow-down-line"></i> -2.4%
        </small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img src="<?= ASSETS_PATH ?>/assets/img/icons/unicons/chart.png" alt="Credit Card" class="rounded" />
          </div>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="cardOpt4"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
              <a class="dropdown-item" href="javascript:void(0);">View All</a>
              <a class="dropdown-item" href="javascript:void(0);">Export</a>
            </div>
          </div>
        </div>
        <span>Total Storage</span>
        <h3 class="card-title text-nowrap mb-1"><?php echo $totalStorage ?? '0 MB'; ?></h3>
        <small class="text-success fw-semibold">
          <i class="icon-base ri ri-arrow-up-line"></i> +8.2%
        </small>
      </div>
    </div>
  </div>


</div> 