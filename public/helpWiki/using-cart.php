<?php
// Include config and helper functions
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Set the page title for the header
$page_title = "Using Your Bag";

// Start output buffering
ob_start();
?>

<!-- 🔹 Main Content -->
<div class="help-container">
    <!-- Page heading -->
    <h1>Using Your Bag</h1>

    <!-- Short description -->
    <p>Your bag is where you keep the items you want to buy:</p>

    <!-- Steps to manage the bag -->
    <ul>
        <li>Increase or decrease item quantity when you choose your product with the right option you want.</li>
        <li>Remove items by clicking “Remove”.</li>
        <li>Click Proceed to Checkout to place your order, enter the required fields, and click on Confirm & Pay (only works if you are logged in).</li>
    </ul>

    <!-- Back to home button -->
    <a href="../index.php" class="btn btn-secondary">⬅ Back to Home</a>
</div>

<?php
// Get page content
$content = ob_get_clean();

// Include header and footer for consistent layout and theme support
include '../../includes/header.php';
echo $content;
include '../../includes/footer.php';
?>
