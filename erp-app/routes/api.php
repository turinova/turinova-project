<?php
/**
 * API Routes
 * 
 * Handles JSON API endpoints for future use
 */

header('Content-Type: application/json');

// API routes
$apiRoutes = [
    '/api/products' => ['ProductController', 'apiIndex'],
    '/api/products/create' => ['ProductController', 'apiStore'],
    '/api/products/update' => ['ProductController', 'apiUpdate'],
    '/api/products/delete' => ['ProductController', 'apiDelete'],
    '/api/users' => ['UserController', 'apiIndex'],
    '/api/users/create' => ['UserController', 'apiStore'],
    '/api/users/update' => ['UserController', 'apiUpdate'],
    '/api/users/delete' => ['UserController', 'apiDelete'],
    '/api/reports' => ['ReportController', 'apiIndex'],
];

// Check if API route exists
if (isset($apiRoutes[$path])) {
    [$controller, $method] = $apiRoutes[$path];
    $controllerFile = "../app/controllers/{$controller}.php";
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controllerInstance = new $controller();
        $controllerInstance->$method();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Controller not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'API endpoint not found']);
}
?> 