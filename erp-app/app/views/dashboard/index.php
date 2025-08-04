<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Üdvözöljük! 🎉</h5>
            <p class="mb-4">A Turinova ERP rendszer vezérlőpultja. Itt tekintheti át a rendszer állapotát és a legfontosabb információkat.</p>

            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Új termék hozzáadása</a>
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
              id="cardOpt3"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
              <a class="dropdown-item" href="javascript:void(0);">Részletek</a>
              <a class="dropdown-item" href="javascript:void(0);">Exportálás</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Termékek</span>
        <h3 class="card-title mb-2"><?= $stats['total_products'] ?></h3>
        <small class="text-success fw-semibold">
          <i class="icon-base ri ri-arrow-up-line"></i> +72.80%
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
              id="cardOpt6"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
              <a class="dropdown-item" href="javascript:void(0);">Részletek</a>
              <a class="dropdown-item" href="javascript:void(0);">Exportálás</a>
            </div>
          </div>
        </div>
        <span>Felhasználók</span>
        <h3 class="card-title text-nowrap mb-1"><?= $stats['total_users'] ?></h3>
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
              id="cardOpt4"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">
              <i class="icon-base ri ri-more-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
              <a class="dropdown-item" href="javascript:void(0);">Részletek</a>
              <a class="dropdown-item" href="javascript:void(0);">Exportálás</a>
            </div>
          </div>
        </div>
        <span class="d-block mb-1">Rendelések</span>
        <div class="d-flex align-items-center">
          <h3 class="card-title text-nowrap mb-0 me-2">0</h3>
          <small class="text-danger fw-semibold">
            <i class="icon-base ri ri-arrow-down-line"></i> -4.3%
          </small>
        </div>
        <small class="text-muted">Aktív rendelések</small>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <img src="<?= ASSETS_PATH ?>/assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
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
            <div class="dropdown-menu" aria-labelledby="cardOpt1">
              <a class="dropdown-item" href="javascript:void(0);">Részletek</a>
              <a class="dropdown-item" href="javascript:void(0);">Exportálás</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Bevétel</span>
        <h3 class="card-title mb-2">0 Ft</h3>
        <small class="text-success fw-semibold">
          <i class="icon-base ri ri-arrow-up-line"></i> +28.14%
        </small>
      </div>
    </div>
  </div>
  <div class="col-12 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-md-6">
          <h5 class="card-header m-0 me-2 pt-2 pb-2">Legutóbbi termékek</h5>
          <div class="card-body pt-3">
            <ul class="timeline">
              <?php if (!empty($stats['recent_products'])): ?>
                <?php foreach ($stats['recent_products'] as $product): ?>
                  <li class="timeline-item timeline-item-translate">
                    <span class="timeline-point timeline-point-indicator"></span>
                    <div class="timeline-event">
                      <div class="timeline-header">
                        <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($product['sku']) ?></small>
                      </div>
                      <p class="timeline-content"><?= number_format($product['price'], 0, ',', ' ') ?> Ft</p>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="timeline-item timeline-item-translate">
                  <span class="timeline-point timeline-point-indicator"></span>
                  <div class="timeline-event">
                    <div class="timeline-header">
                      <h6 class="mb-0">Nincsenek termékek</h6>
                    </div>
                    <p class="timeline-content">Még nincsenek hozzáadott termékek a rendszerben.</p>
                  </div>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <h5 class="card-header m-0 me-2 pt-2 pb-2">Legutóbbi felhasználók</h5>
          <div class="card-body pt-3">
            <ul class="timeline">
              <?php if (!empty($stats['recent_users'])): ?>
                <?php foreach ($stats['recent_users'] as $user): ?>
                  <li class="timeline-item timeline-item-translate">
                    <span class="timeline-point timeline-point-indicator"></span>
                    <div class="timeline-event">
                      <div class="timeline-header">
                        <h6 class="mb-0"><?= htmlspecialchars($user['name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                      </div>
                      <p class="timeline-content"><?= htmlspecialchars($user['role']) ?></p>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="timeline-item timeline-item-translate">
                  <span class="timeline-point timeline-point-indicator"></span>
                  <div class="timeline-event">
                    <div class="timeline-header">
                      <h6 class="mb-0">Nincsenek felhasználók</h6>
                    </div>
                    <p class="timeline-content">Még nincsenek regisztrált felhasználók a rendszerben.</p>
                  </div>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 