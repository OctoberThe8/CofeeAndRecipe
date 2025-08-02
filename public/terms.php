<?php
// Start or resume the session
session_start();

// Include configuration and helper functions
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set page title for the terms and conditions page
$page_title = 'Terms & Conditions';

// Start output buffering to capture the page content
ob_start();
?>

<!-- Link to a CSS file specifically for styling the Terms & Conditions page -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/terms.css">

<div class="terms-container">
    <h1>Terms & Conditions</h1>
    
    <!-- Section describing user agreement -->
    <section>
        <h2>1. Acceptance of Terms</h2>
        <p>By accessing or using our website, you agree to be bound by these Terms and all applicable laws and regulations. If you do not agree with any of these terms, please do not use our site.</p>
    </section>

    <!-- Section explaining proper usage of the site -->
    <section>
        <h2>2. Use of Site</h2>
        <p>You may use our site for lawful purposes only. You are responsible for any activity that occurs under your account and must keep your login information secure.</p>
    </section>

    <!-- Section clarifying product information and accuracy -->
    <section>
        <h2>3. Product Information</h2>
        <p>We strive to provide accurate information, but we do not warrant that product descriptions or content are error-free. Pricing and availability are subject to change without notice.</p>
    </section>

    <!-- Section describing order and payment policies -->
    <section>
        <h2>4. Orders & Payment</h2>
        <p>All purchases made through our site are subject to product availability and confirmation of payment. We reserve the right to refuse or cancel any order for any reason.</p>
    </section>

    <!-- Section about ownership of intellectual property -->
    <section>
        <h2>5. Intellectual Property</h2>
        <p>All content on this site—including text, images, logos, and recipes—is our property or used with permission. You may not copy or use it without prior consent.</p>
    </section>

    <!-- Section limiting company liability -->
    <section>
        <h2>6. Limitation of Liability</h2>
        <p>We are not liable for any indirect or consequential damages that may arise from using our site or products. Our liability is limited to the amount paid for your order.</p>
    </section>

    <!-- Section explaining that terms may change -->
    <section>
        <h2>7. Changes to Terms</h2>
        <p>We reserve the right to modify these terms at any time. Changes will be posted on this page with an updated revision date. Continued use of the site means you accept the new terms.</p>
    </section>

    <!-- Section with contact information -->
    <section>
        <h2>8. Contact Us</h2>
        <p>If you have any questions or concerns about these Terms, please <a href="<?php echo SITE_URL; ?>/public/contact.php">contact us</a>. We're here to help!</p>
    </section>

    <!-- Last updated date -->
    <p class="last-updated">Last Updated: July 2025</p>
</div>

<?php
// Store all buffered output in $content
$content = ob_get_clean();

// Include the header
include '../includes/header.php';

// Display the page content
echo $content;

// Include the footer
include '../includes/footer.php';
?>
