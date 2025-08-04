<?php
// Simple test to verify layout system
session_start();

// Mock authentication
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Test Admin';
$_SESSION['user_role'] = 'admin';

// Set page title
$title = 'Test Page - SaaS Management';

// Mock content
ob_start();
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Test Page</h5>
            </div>
            <div class="card-body">
                <p>This is a test page to verify the layout system is working properly.</p>
                <p>If you can see this content with the menu on the left and navbar on top, the layout is working!</p>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();

// Include the layout
include 'app/views/layout/base.php';
?> 