<?php
// Include configuration and helper functions
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Set the page title for the dynamic header
$page_title = "Shopping Help";

// Start output buffering
ob_start();
?>

<!--Main Content -->
<div class="help-container">
    <!-- Page Heading -->
    <h1>Shopping Help</h1>

    <!-- Short description -->
    <p>This guide explains how to find and purchase products:</p>

    <!-- Steps for shopping -->
    <ul>
        <li>Browse through the Coffee or Recipes sections.</li>
        <li>Click on a product to see details and add it to your bag.</li>
        <li>Go to your bag to review and complete your order.</li>
    </ul>

    <!-- Button to go back to home page -->
    <a href="../index.php" class="btn btn-secondary">⬅ Back to Home</a>
</div>

<?php
// Get the page content
$content = ob_get_clean();

// Include the common site header
include '../../includes/header.php';

// Display the page content
echo $content;

// Include the common site footer
include '../../includes/footer.php';
?>
