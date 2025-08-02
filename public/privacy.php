<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set the page title for use in the header
$page_title = 'Privacy Policy';

// Start output buffering to capture the page content
ob_start();
?>

<!-- Link to a dedicated CSS file for privacy policy page styling -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/privacy.css">

<div class="privacy-container">
    <div class="privacy-card">
        <h1>Privacy Policy</h1>

        <!-- Introduction about the site's privacy practices -->
        <p>At <strong>Coffee &amp; Recipes</strong>, your privacy is our priority. This policy explains how we collect, use, and protect your data.</p>

        <!-- Section describing what information is collected -->
        <h2>1. Information We Collect</h2>
        <ul>
            <li>Your name, email, and address when you register or checkout</li>
            <li>Payment details via secure third-party processors</li>
            <li>Anonymous data for analytics like IP and browser info</li>
        </ul>

        <!-- Section describing how collected data is used -->
        <h2>2. How We Use It</h2>
        <ul>
            <li>To complete orders and send confirmation emails</li>
            <li>To improve site functionality and user experience</li>
            <li>To notify you of promotions (opt-out available)</li>
        </ul>

        <!-- Section explaining data sharing practices -->
        <h2>3. Sharing Data</h2>
        <p>We never sell your info. We only share with payment processors, couriers, or by legal obligation.</p>

        <!-- Section about cookie usage -->
        <h2>4. Cookies</h2>
        <p>We use cookies to save preferences (like theme) and cart data.</p>

        <!-- Section about security measures -->
        <h2>5. Security</h2>
        <p>Your data is encrypted and stored securely. You are responsible for keeping your password safe.</p>

        <!-- Section about user control over their data -->
        <h2>6. Your Choices</h2>
        <ul>
            <li>Edit or delete your account any time</li>
            <li>Control cookies via browser settings</li>
        </ul>

        <!-- Section describing that policy may change -->
        <h2>7. Policy Updates</h2>
        <p>We may update this page. Continuing to use the site means you agree to the latest version.</p>

        <!-- Contact information for questions -->
        <h2>8. Contact</h2>
        <p>Questions? <a href="<?php echo SITE_URL; ?>/public/contact.php">Contact us</a>.</p>

        <!-- Back button to return to homepage -->
        <div class="back-button">
            <a href="<?php echo SITE_URL; ?>/public/index.php">&larr; Back to Home</a>
        </div>
    </div>
</div>

<?php
// Store all buffered content into $content
$content = ob_get_clean();

// Include the header
include '../includes/header.php';

// Output the page content
echo $content;

// Include the footer
include '../includes/footer.php';
?>
