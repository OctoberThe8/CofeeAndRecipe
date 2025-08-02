<?php
// Start the session to manage user authentication
session_start();

// Include configuration and authentication class
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Create an Auth instance to verify if the user is logged in and an admin
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    // Redirect non-admin users to login page
    header("Location: ../public/login.php");
    exit;
}

// Set the page title
$page_title = "Theme Settings";

// Start output buffering to store page content
ob_start();
?>
<h1>🎨 Theme Settings</h1>
<div class="theme-container">
  <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>

  <h2>Theme Settings</h2>
  <p>Select a theme for the admin panel:</p>

    <p>
        <i>NOTE:</i> Once you click on the theme it will go the main web page, if you want to return to the admin panel, click the <strong>Admin</strong> button below Orders and above Logout.
    </p>
    <br>
    <br>
  <!-- Theme selection buttons -->
  <div class="theme-buttons">
    <a href="<?php echo SITE_URL; ?>/public/change-theme.php?theme=regular">Regular</a>
    <a href="<?php echo SITE_URL; ?>/public/change-theme.php?theme=halloween">Halloween</a>
    <a href="<?php echo SITE_URL; ?>/public/change-theme.php?theme=Christmas">Christmas</a>
    <a href="<?php echo SITE_URL; ?>/public/change-theme.php?theme=easter">Easter</a>
  </div>  
</div>

<?php
// Capture all buffered content
$content = ob_get_clean();

// Include the admin header, page content, and footer
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
