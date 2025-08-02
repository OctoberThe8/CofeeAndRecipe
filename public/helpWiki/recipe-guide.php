<?php
// Load configuration and helper functions
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Set page title for dynamic header
$page_title = "Recipe Saving & Sharing Guide";

// Start capturing the page content
ob_start();
?>

<div class="help-container">
    <!-- Page Heading -->
    <h1>Recipe Saving & Sharing Guide</h1>

    <!-- Guide Description -->
    <p>With your account, you can save and share your favorite recipes:</p>

    <!-- Instructions List -->
    <ul>
        <li>Click “Save Recipe” on any recipe page to store it in your account (only for logged-in users).</li>
        <li>Go to “Favorites” under your profile to see saved recipes.</li>
        <li>Click “Share” to access a form where you can fill in details and send it to friends.</li>
    </ul>

    <!-- Button to return to homepage -->
    <a href="../index.php" class="btn btn-secondary">⬅ Back to Home</a>
</div>

<?php
// Save the buffered content into $content
$content = ob_get_clean();

// Include the common header
include '../../includes/header.php';

// Output the page content
echo $content;

// Include the common footer
include '../../includes/footer.php';
?>
