<?php
// Load config and functions so SITE_URL and getCurrentTheme() work
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Set the page title
$page_title = "Managing Your Account";

// Start output buffering to store the page content
ob_start();
?>

<div class="help-container">
    <h1>Managing Your Account</h1>
    <p>Inside your account page, you can:</p>
    <ul>
        <li>Update your username or email address.</li>
        <li>Update your first name and last name.</li>
        <li>Check your previous orders and saved recipes.</li>
    </ul>
    <a href="../index.php" class="btn btn-secondary">⬅ Back to Home</a>
</div>

<?php
// Store the content and then include the header and footer
$content = ob_get_clean();
include '../../includes/header.php';
echo $content;
include '../../includes/footer.php';
?>
