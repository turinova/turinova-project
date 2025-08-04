<?php
/**
 * Web Routes
 * 
 * This file defines all the web routes for the application
 */

// Define routes
$routes = [
    '/dashboard' => ['DashboardController', 'index'],
    '/login' => ['AuthController', 'login'],
    '/logout' => ['AuthController', 'logout'],
    '/users' => ['UserController', 'index'],
    '/users/add' => ['UserController', 'add'],
    '/users/permissions' => ['UserController', 'getPermissions'],
    '/users/permissions/update' => ['UserController', 'updatePermissions'],
    '/users/password' => ['UserController', 'changePassword'],
    '/users/delete' => ['UserController', 'delete'],
    '/products' => ['ProductController', 'index'],
    '/partners' => ['PartnerController', 'index'],
    '/shipments' => ['ShipmentController', 'index'],
    '/supplier-orders' => ['SupplierOrderController', 'index'],
    '/warehouse' => ['WarehouseController', 'index'],
    '/customers' => ['CustomerController', 'index'],
    '/pricing-rules' => ['PricingRuleController', 'index'],
    '/product-categories' => ['ProductCategoryController', 'index'],
    '/manufacturers' => ['ManufacturersController', 'index'],
    '/units' => ['UnitsController', 'index'],
    '/warehouses' => ['WarehouseController', 'index'],
    '/payment-methods' => ['PaymentMethodsController', 'index'],
    '/customer-groups' => ['CustomerGroupController', 'index'],
    '/shelves' => ['ShelvesController', 'index'],
    '/pos' => ['PosController', 'index'],
    '/media' => ['MediaController', 'index'],
    '/sales' => ['SalesController', 'index'],
    '/returns' => ['ReturnsController', 'index'],
    '/offers' => ['OffersController', 'index'],
    '/reports' => ['ReportsController', 'index'],
    '/positions' => ['PositionsController', 'index'],
    '/employees' => ['EmployeesController', 'index'],
    '/performance' => ['PerformanceController', 'index'],
    '/operational-settings' => ['OperationalSettingsController', 'index'],
    '/company-data' => ['CompanyDataController', 'index'],
    '/company-data/update' => ['CompanyDataController', 'update'],
    '/vat' => ['VatController', 'index'],
    '/currencies' => ['CurrenciesController', 'index'],
    '/sources' => ['SourcesController', 'index'],
    '/return-reasons' => ['ReturnReasonsController', 'index'],
    '/fee-types' => ['FeeTypesController', 'index'],
    '/cancellation-reasons' => ['CancellationReasonsController', 'index'],
    '/shipping-methods' => ['ShippingMethodsController', 'index'],
];

// Route handling
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove the base path from the URL
$base_path = BASE_PATH;
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Remove .php extension if present
$path = preg_replace('/\.php$/', '', $path);

// If no path is specified, default to dashboard
if ($path === '' || $path === '/') {
    $path = '/dashboard';
}

// Get the current path
$currentPath = $path ?? '';

// Check if route exists
if (isset($routes[$currentPath])) {
    [$controllerName, $methodName] = $routes[$currentPath];
    // Only allow POST for update route
    if ($currentPath === '/company-data/update' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /company-data');
        exit;
    }
    // Load the controller
    require_once "../app/controllers/{$controllerName}.php";
    // Create controller instance and call method
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    // 404 - Route not found
    http_response_code(404);
    echo "404 - Page not found";
}
?> 