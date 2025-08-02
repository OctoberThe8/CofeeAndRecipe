<?php
// Load config and functions to get SITE_URL and theme
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Set the page title
$page_title = "Account Setup Guide";

// Start output buffering to store page content
ob_start();
?>

<div class="help-container">
    <h1>Account Setup Guide</h1>
    <p>To create a new account:</p>
    <ol>
        <li>Click on “Sign Up” at the top right corner.</li>
        <li>Enter your username, email, and password.</li>
        <li>Click “Create Account” to finish the setup.</li>
    </ol>

    <!-- Button to navigate back to homepage -->
    <a href="../index.php" class="btn btn-secondary">⬅ Back to Home</a>
</div>

<?php
// Store the page content in $content
$content = ob_get_clean();

// Include the shared header
include '../../includes/header.php';

// Display the content
echo $content;

// Include the shared footer
include '../../includes/footer.php';
?>
