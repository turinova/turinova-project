<?php
// Get current path for active menu highlighting
$current_path = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($current_path, PHP_URL_PATH);

// Extract the page name from the path for comparison
$current_page = '';
if (preg_match('/\/([^\/]+)\.php$/', $path, $matches)) {
    $current_page = $matches[1];
}

// Map URL page names to database page names for proper comparison
$page_name_mapping = [
    'supplier-orders' => 'supplier_orders',
    'pricing-rules' => 'pricing_rules',
    'product-categories' => 'product_categories',
    'warehouses' => 'warehouses',
    'payment-methods' => 'payment_methods',
    'customer-groups' => 'customer_groups',
    'operational-settings' => 'operational_settings',
    'company-data' => 'company_data',
    'return-reasons' => 'return_reasons',
    'fee-types' => 'fee_types',
    'cancellation-reasons' => 'cancellation_reasons',
    'shipping-methods' => 'shipping_methods'
];

// Convert URL page name to database page name
if (isset($page_name_mapping[$current_page])) {
    $current_page = $page_name_mapping[$current_page];
}

// Check user permissions for menu items
$user_permissions = [];
if (isset($_SESSION['user_id'])) {
    try {
        // Load database connection safely
        require_once __DIR__ . '/../../../database/connection.php';
        global $db;
        
        // Get user permissions for all pages
        $permissions = $db->fetchAll("
            SELECT p.name, up.can_access
            FROM pages p
            LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
            ORDER BY p.menu_order
        ", [$_SESSION['user_id']]);
        
        foreach ($permissions as $perm) {
            $user_permissions[$perm['name']] = $perm['can_access'] ?? 0;
        }
    } catch (Exception $e) {
        // If database connection fails, default to no permissions
        $user_permissions = ['dashboard' => 0, 'users' => 0];
    }
}
?>
<!doctype html>

<html
  lang="hu"
  class="layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-skin="default"
  data-bs-theme="light"
  data-assets-path="<?= ASSETS_PATH ?>/assets/"
  data-template="vertical-menu-template-no-customizer">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title><?= $title ?? APP_NAME ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= ASSETS_PATH ?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?= ASSETS_PATH ?>/assets/vendor/libs/select2/select2.css" />

    <!-- Helpers -->
    <script src="<?= ASSETS_PATH ?>/assets/vendor/js/helpers.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu">
          <div class="app-brand demo">
            <a href="<?= ERP_BASE_URL ?>/dashboard.php" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                  <svg width="32" height="18" viewBox="0 0 38 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                      fill="currentColor" />
                    <path
                      d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                      fill="url(#paint0_linear_2989_100980)"
                      fill-opacity="0.4" />
                    <path
                      d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                      fill="currentColor" />
                    <path
                      d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                      fill="currentColor" />
                    <path
                      d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                      fill="url(#paint1_linear_2989_100980)"
                      fill-opacity="0.4" />
                    <path
                      d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                      fill="currentColor" />
                    <defs>
                      <linearGradient
                        id="paint0_linear_2989_100980"
                        x1="5.36642"
                        y1="0.849138"
                        x2="10.532"
                        y2="24.104"
                        gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-opacity="1" />
                        <stop offset="1" stop-opacity="0" />
                      </linearGradient>
                      <linearGradient
                        id="paint1_linear_2989_100980"
                        x1="5.19475"
                        y1="0.849139"
                        x2="10.3357"
                        y2="24.1155"
                        gradientUnits="userSpaceOnUse">
                        <stop offset="0" stop-opacity="1" />
                        <stop offset="1" stop-opacity="0" />
                      </linearGradient>
                    </defs>
                  </svg>
                </span>
              </span>
              <span class="app-brand-text demo text-heading fw-semibold">Turinova ERP</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="icon-base ri ri-menu-line icon-22px"></i>
            </a>
          </div>

          <div class="menu-inner py-1">
            <?php
            // Get all pages with user permissions
            $userPages = $db->fetchAll("
                SELECT p.name, p.title, p.route, p.icon, p.menu_order,
                       COALESCE(up.can_access, 0) as can_access
                FROM pages p
                LEFT JOIN user_permissions up ON p.id = up.page_id AND up.user_id = ?
                ORDER BY p.menu_order
            ", [$_SESSION['user_id']]);
            
            // Separate regular pages from settings pages and procurement pages
            $regularPages = [];
            $settingsPages = [];
            $procurementPages = [];
            
            foreach ($userPages as $page):
                if ($page['can_access']):
                    if ($page['name'] === 'users') {
                        $settingsPages[] = $page;
                    } elseif ($page['name'] === 'operational_settings') {
                        $settingsPages[] = $page;
                    } elseif ($page['name'] === 'company_data') {
                        $settingsPages[] = $page;
                    } elseif (in_array($page['name'], ['supplier_orders', 'shipments'])) {
                        $procurementPages[] = $page;
                    } elseif ($page['name'] === 'partners') {
                        // Partners will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'product_categories') {
                        // Product categories will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'products') {
                        // Products will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif (in_array($page['name'], ['manufacturers', 'units'])) {
                        // Product master data pages will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'pricing_rules') {
                        // Pricing rules will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'warehouses') {
                        // Warehouses will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'payment_methods') {
                        // Payment methods will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'customer_groups') {
                        // Customer groups will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'shelves') {
                        // Shelves will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'pos') {
                        // POS will be handled in the regular pages section
                        $regularPages[] = $page;
                    } elseif ($page['name'] === 'media') {
                        // Media will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'sales') {
                        // Sales will be handled in the regular pages section
                        $regularPages[] = $page;
                    } elseif ($page['name'] === 'returns') {
                        // Returns will be handled in the regular pages section
                        $regularPages[] = $page;
                    } elseif ($page['name'] === 'offers') {
                        // Offers will be handled in the regular pages section
                        $regularPages[] = $page;
                    } elseif ($page['name'] === 'reports') {
                        // Reports will be handled in the regular pages section
                        $regularPages[] = $page;
                    } elseif (in_array($page['name'], ['positions', 'employees', 'performance'])) {
                        // HR pages will be handled in the HR menu section
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'vat') {
                        // VAT will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'currencies') {
                        // Currencies will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'sources') {
                        // Sources will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'return_reasons') {
                        // Return reasons will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'fee_types') {
                        // Fee types will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'cancellation_reasons') {
                        // Cancellation reasons will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } elseif ($page['name'] === 'shipping_methods') {
                        // Shipping methods will be handled in the Törzsadatok menu - don't add to regular pages
                        // Skip adding to regularPages array
                    } else {
                        $regularPages[] = $page;
                    }
                endif;
            endforeach;
            
            // Display regular pages
            foreach ($regularPages as $page):
            ?>
            <li class="menu-item<?= ($current_page === $page['name']) ? ' active' : '' ?>">
                <a href="<?= ERP_BASE_URL ?><?= $page['route'] ?>.php" class="menu-link">
                    <i class="menu-icon icon-base ri <?= $page['icon'] ?>"></i>
                    <div data-i18n="<?= $page['title'] ?>"><?= $page['title'] ?></div>
                </a>
            </li>
            <?php 
            endforeach;
            
            // Display settings menu with submenu
            if (!empty($settingsPages)):
                // Check if current page is a settings page
                $isSettingsPage = in_array($current_page, array_column($settingsPages, 'name'));
            ?>
            <li class="menu-item<?= $isSettingsPage ? ' active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-settings-line"></i>
                    <div data-i18n="Beállítások">Beállítások</div>
                </a>
                <ul class="menu-sub">
                    <?php foreach ($settingsPages as $page): ?>
                    <li class="menu-item<?= ($current_page === $page['name']) ? ' active' : '' ?>">
                        <a href="<?= ERP_BASE_URL ?><?= $page['route'] ?>.php" class="menu-link">
                            <div data-i18n="<?= $page['title'] ?>"><?= $page['title'] ?></div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php 
            endif;
            
            // Display procurement menu with submenu
            if (!empty($procurementPages)):
                // Check if current page is a procurement page
                $isProcurementPage = in_array($current_page, array_column($procurementPages, 'name'));
            ?>
            <li class="menu-item<?= $isProcurementPage ? ' active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-shopping-cart-line"></i>
                    <div data-i18n="Beszerzés">Beszerzés</div>
                </a>
                <ul class="menu-sub">
                    <?php foreach ($procurementPages as $page): ?>
                    <li class="menu-item<?= ($current_page === $page['name']) ? ' active' : '' ?>">
                        <a href="<?= ERP_BASE_URL ?><?= $page['route'] ?>.php" class="menu-link">
                            <div data-i18n="<?= $page['title'] ?>"><?= $page['title'] ?></div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php 
            endif;
            
            // Display HR menu with submenu
            $hrPages = [];
            foreach ($userPages as $page) {
                if (in_array($page['name'], ['positions', 'employees', 'performance']) && $page['can_access']) {
                    $hrPages[] = $page;
                }
            }
            if (!empty($hrPages)):
                // Check if current page is an HR page
                $isHrPage = in_array($current_page, ['positions', 'employees', 'performance']);
            ?>
            <li class="menu-item<?= $isHrPage ? ' active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-team-line"></i>
                    <div data-i18n="Emberi erőforrás">Emberi erőforrás</div>
                </a>
                <ul class="menu-sub">
                    <?php foreach ($hrPages as $page): ?>
                    <li class="menu-item<?= ($current_page === $page['name']) ? ' active' : '' ?>">
                        <a href="<?= ERP_BASE_URL ?><?= $page['route'] ?>.php" class="menu-link">
                            <div data-i18n="<?= $page['title'] ?>"><?= $page['title'] ?></div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php 
            endif;
            // Display Törzsadatok menu with Level 2 submenus
            // Check if current page is a master data page
            $isMasterDataPage = in_array($current_page, ["partners", "products", "product_categories", "manufacturers", "units", "media", "pricing_rules", "sources", "return_reasons", "warehouses", "payment_methods", "customer_groups", "shelves", "vat", "currencies", "fee_types", "cancellation_reasons", "shipping_methods"]);
            
            // Check if user has access to any master data pages
            $hasMasterDataAccess = false;
            foreach ($userPages as $page) {
                if (in_array($page["name"], ["partners", "products", "product_categories", "manufacturers", "units", "media", "pricing_rules", "sources", "return_reasons", "warehouses", "payment_methods", "customer_groups", "shelves", "vat", "currencies", "fee_types", "cancellation_reasons", "shipping_methods"]) && $page["can_access"]) {
                    $hasMasterDataAccess = true;
                    break;
                }
            }
            
            if ($hasMasterDataAccess):
            ?>
            <li class="menu-item<?= $isMasterDataPage ? " active open" : "" ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ri ri-database-line"></i>
                    <div data-i18n="Törzsadatok">Törzsadatok</div>
                </a>
                <ul class="menu-sub">
                    <!-- Partnerek Level 2 -->
                    <?php
                    // Check if user has access to any partner-related pages
                    $hasPartnerAccess = false;
                    foreach ($userPages as $page) {
                        if (in_array($page["name"], ["partners", "customer_groups"]) && $page["can_access"]) {
                            $hasPartnerAccess = true;
                            break;
                        }
                    }
                    if ($hasPartnerAccess):
                    ?>
                    <li class="menu-item<?= (in_array($current_page, ["partners", "customer_groups"])) ? " active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Partnerek">Partnerek</div>
                        </a>
                        <ul class="menu-sub">
                            <?php 
                            // Check if user has access to partners page
                            $partnersPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "partners" && $page["can_access"]) {
                                    $partnersPage = $page;
                                    break;
                                }
                            }
                            if ($partnersPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "partners") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $partnersPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $partnersPage["title"] ?>"><?= $partnersPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to customer groups page
                            $customerGroupsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "customer_groups" && $page["can_access"]) {
                                    $customerGroupsPage = $page;
                                    break;
                                }
                            }
                            if ($customerGroupsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "customer_groups") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $customerGroupsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $customerGroupsPage["title"] ?>"><?= $customerGroupsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Termékek Level 2 -->
                    <?php
                    // Check if user has access to any product-related pages
                    $hasProductAccess = false;
                    foreach ($userPages as $page) {
                        if (in_array($page["name"], ["products", "product_categories", "manufacturers", "units", "media"]) && $page["can_access"]) {
                            $hasProductAccess = true;
                            break;
                        }
                    }
                    if ($hasProductAccess):
                    ?>
                    <li class="menu-item<?= (in_array($current_page, ["products", "product_categories", "manufacturers", "units", "media"])) ? " active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Termékek">Termékek</div>
                        </a>
                        <ul class="menu-sub">
                            <?php 
                            // Check if user has access to products page
                            $productsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "products" && $page["can_access"]) {
                                    $productsPage = $page;
                                    break;
                                }
                            }
                            if ($productsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "products") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $productsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $productsPage["title"] ?>"><?= $productsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to product categories page
                            $productCategoriesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "product_categories" && $page["can_access"]) {
                                    $productCategoriesPage = $page;
                                    break;
                                }
                            }
                            if ($productCategoriesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "product_categories") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $productCategoriesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $productCategoriesPage["title"] ?>"><?= $productCategoriesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to manufacturers page
                            $manufacturersPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "manufacturers" && $page["can_access"]) {
                                    $manufacturersPage = $page;
                                    break;
                                }
                            }
                            if ($manufacturersPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "manufacturers") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $manufacturersPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $manufacturersPage["title"] ?>"><?= $manufacturersPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to units page
                            $unitsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "units" && $page["can_access"]) {
                                    $unitsPage = $page;
                                    break;
                                }
                            }
                            if ($unitsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "units") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $unitsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $unitsPage["title"] ?>"><?= $unitsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to media page
                            $mediaPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "media" && $page["can_access"]) {
                                    $mediaPage = $page;
                                    break;
                                }
                            }
                            if ($mediaPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "media") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $mediaPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $mediaPage["title"] ?>"><?= $mediaPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Értékesítés Level 2 -->
                    <?php
                    // Check if user has access to any sales-related pages
                    $hasSalesAccess = false;
                    foreach ($userPages as $page) {
                        if (in_array($page["name"], ["pricing_rules", "sources", "return_reasons", "fee_types", "cancellation_reasons", "shipping_methods"]) && $page["can_access"]) {
                            $hasSalesAccess = true;
                            break;
                        }
                    }
                    if ($hasSalesAccess):
                    ?>
                    <li class="menu-item<?= (in_array($current_page, ["pricing_rules", "sources", "return_reasons", "fee_types", "cancellation_reasons", "shipping_methods"])) ? " active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Értékesítés">Értékesítés</div>
                        </a>
                        <ul class="menu-sub">
                            <?php 
                            // Check if user has access to pricing rules page
                            $pricingRulesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "pricing_rules" && $page["can_access"]) {
                                    $pricingRulesPage = $page;
                                    break;
                                }
                            }
                            if ($pricingRulesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "pricing_rules") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $pricingRulesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $pricingRulesPage["title"] ?>"><?= $pricingRulesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to sources page
                            $sourcesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "sources" && $page["can_access"]) {
                                    $sourcesPage = $page;
                                    break;
                                }
                            }
                            if ($sourcesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "sources") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $sourcesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $sourcesPage["title"] ?>"><?= $sourcesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php 
                            // Check if user has access to return reasons page
                            $returnReasonsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "return_reasons" && $page["can_access"]) {
                                    $returnReasonsPage = $page;
                                    break;
                                }
                            }
                            if ($returnReasonsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "return_reasons") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $returnReasonsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $returnReasonsPage["title"] ?>"><?= $returnReasonsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php 
                            // Check if user has access to fee types page
                            $feeTypesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "fee_types" && $page["can_access"]) {
                                    $feeTypesPage = $page;
                                    break;
                                }
                            }
                            if ($feeTypesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "fee_types") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $feeTypesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $feeTypesPage["title"] ?>"><?= $feeTypesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php 
                            // Check if user has access to cancellation reasons page
                            $cancellationReasonsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "cancellation_reasons" && $page["can_access"]) {
                                    $cancellationReasonsPage = $page;
                                    break;
                                }
                            }
                            if ($cancellationReasonsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "cancellation_reasons") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $cancellationReasonsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $cancellationReasonsPage["title"] ?>"><?= $cancellationReasonsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php 
                            // Check if user has access to shipping methods page
                            $shippingMethodsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "shipping_methods" && $page["can_access"]) {
                                    $shippingMethodsPage = $page;
                                    break;
                                }
                            }
                            if ($shippingMethodsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "shipping_methods") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $shippingMethodsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $shippingMethodsPage["title"] ?>"><?= $shippingMethodsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Áruforgalom Level 2 -->
                    <?php
                    // Check if user has access to any warehouse-related pages
                    $hasWarehouseAccess = false;
                    foreach ($userPages as $page) {
                        if (in_array($page["name"], ["warehouses", "shelves"]) && $page["can_access"]) {
                            $hasWarehouseAccess = true;
                            break;
                        }
                    }
                    if ($hasWarehouseAccess):
                    ?>
                    <li class="menu-item<?= (in_array($current_page, ["warehouses", "shelves"])) ? " active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Áruforgalom">Áruforgalom</div>
                        </a>
                        <ul class="menu-sub">
                            <?php 
                            // Check if user has access to warehouses page
                            $warehousesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "warehouses" && $page["can_access"]) {
                                    $warehousesPage = $page;
                                    break;
                                }
                            }
                            if ($warehousesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "warehouses") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $warehousesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $warehousesPage["title"] ?>"><?= $warehousesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to shelves page
                            $shelvesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "shelves" && $page["can_access"]) {
                                    $shelvesPage = $page;
                                    break;
                                }
                            }
                            if ($shelvesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "shelves") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $shelvesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $shelvesPage["title"] ?>"><?= $shelvesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Pénzügy Level 2 -->
                    <?php
                    // Check if user has access to any finance-related pages
                    $hasFinanceAccess = false;
                    foreach ($userPages as $page) {
                        if (in_array($page["name"], ["payment_methods", "vat", "currencies"]) && $page["can_access"]) {
                            $hasFinanceAccess = true;
                            break;
                        }
                    }
                    if ($hasFinanceAccess):
                    ?>
                    <li class="menu-item<?= (in_array($current_page, ["payment_methods", "vat", "currencies"])) ? " active open" : "" ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Pénzügy">Pénzügy</div>
                        </a>
                        <ul class="menu-sub">
                            <?php 
                            // Check if user has access to payment methods page
                            $paymentMethodsPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "payment_methods" && $page["can_access"]) {
                                    $paymentMethodsPage = $page;
                                    break;
                                }
                            }
                            if ($paymentMethodsPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "payment_methods") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $paymentMethodsPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $paymentMethodsPage["title"] ?>"><?= $paymentMethodsPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to VAT page
                            $vatPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "vat" && $page["can_access"]) {
                                    $vatPage = $page;
                                    break;
                                }
                            }
                            if ($vatPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "vat") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $vatPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $vatPage["title"] ?>"><?= $vatPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php 
                            // Check if user has access to currencies page
                            $currenciesPage = null;
                            foreach ($userPages as $page) {
                                if ($page["name"] === "currencies" && $page["can_access"]) {
                                    $currenciesPage = $page;
                                    break;
                                }
                            }
                            if ($currenciesPage):
                            ?>
                            <li class="menu-item<?= ($current_page === "currencies") ? " active" : "" ?>">
                                <a href="<?= ERP_BASE_URL ?><?= $currenciesPage["route"] ?>.php" class="menu-link">
                                    <div data-i18n="<?= $currenciesPage["title"] ?>"><?= $currenciesPage["title"] ?></div>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php 
            endif;
            ?>
          </div>
        </aside>

        <div class="menu-mobile-toggler d-xl-none rounded-1">
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
            <i class="ri ri-menu-line icon-base"></i>
            <i class="ri ri-arrow-right-s-line icon-base"></i>
          </a>
        </div>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav
            class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base ri ri-menu-line icon-22px"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="<?= ASSETS_PATH ?>/assets/img/avatars/1.png" alt="avatar" class="rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img
                                src="<?= ASSETS_PATH ?>/assets/img/avatars/1.png"
                                alt="avatar"
                                class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0"><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User' ?></h6>
                            <small class="text-body-secondary"><?= isset($_SESSION['user_role']) ? htmlspecialchars($_SESSION['user_role']) : 'User' ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <div class="d-grid px-4 pt-2 pb-1">
                        <a class="btn btn-danger d-flex align-items-center justify-content-center" href="javascript:void(0);" onclick="clearCacheAndLogout()">
                          <i class="icon-base ri ri-logout-box-r-line me-2"></i>
                          <span>Kijelentkezés</span>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Flash Messages -->
              <?= Flash::display() ?>
              
              <!-- Page Content -->
              <?php if (isset($content)): ?>
                <?= $content ?>
              <?php endif; ?>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    &#169;
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , <?= APP_NAME ?> - Made with ❤️
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <span class="text-body-secondary">Version <?= APP_VERSION ?></span>
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/@algolia/autocomplete-js.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/libs/select2/select2.js"></script>
    <script src="<?= ASSETS_PATH ?>/assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="<?= ASSETS_PATH ?>/assets/js/main.js"></script>
    
    <!-- Security Script - Prevent back button after logout -->
    <script>
        // Clear browser cache and prevent back button access
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from back-forward cache
                window.location.reload();
            }
        });
        
        // Clear cache on logout
        function clearCacheAndLogout() {
            // Clear browser cache
            if ('caches' in window) {
                caches.keys().then(function(names) {
                    for (let name of names) {
                        caches.delete(name);
                    }
                });
            }
            
            // Navigate to logout
            window.location.href = '<?= ERP_BASE_URL ?>/logout.php';
        }
    </script>
  </body>
</html> 