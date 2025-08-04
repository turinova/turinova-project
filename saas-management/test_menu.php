<?php
// Test to verify menu collapse functionality
session_start();

// Mock authentication
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test Admin';
$_SESSION['user_role'] = 'admin';

// Set page title
$title = 'Menu Test - SaaS Management';

// Mock content
ob_start();
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Menu Collapse Test</h5>
            </div>
            <div class="card-body">
                <p>This page tests the menu collapse functionality.</p>
                <p><strong>Instructions:</strong></p>
                <ul>
                    <li>Click the menu toggle button (hamburger icon) in the sidebar to collapse/expand the menu</li>
                    <li>The menu should collapse and show only icons</li>
                    <li>Click again to expand the menu</li>
                    <li>On mobile, the menu should slide in/out from the left</li>
                </ul>
                <div class="alert alert-info">
                    <i class="icon-base ri ri-information-line me-2"></i>
                    If the menu collapse is working, you should see the menu toggle button in the sidebar and be able to collapse/expand the menu.
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

// Include the layout
include 'app/views/layout/base.php';
?> 