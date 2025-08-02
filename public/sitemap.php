<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = 'Sitemap';
ob_start();
?>

<!-- Link to the sitemap-specific CSS file -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/sitemap.css">

<div class="sitemap-container">
    <h1>Website Sitemap</h1>
    <p>Explore all pages and categories available on our website.</p>

<!-- List of all important pages on the website -->
    <ul class="sitemap-list">
        <li><a href="<?php echo SITE_URL; ?>/public/index.php">🏠 Home</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/products.php">🛒 Products</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/recipes.php">📖 Recipes</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/cart.php">🛍️ View Cart</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/checkout.php">💳 Checkout</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/login.php">🔐 Login</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/register.php">📝 Register</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/contact.php">✉️ Contact Us</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/privacy.php">🔒 Privacy Policy</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/terms.php">📄 Terms & Conditions</a></li>
        <li><a href="<?php echo SITE_URL; ?>/public/about.php">👤 About Us</a></li>
    </ul>
</div>

<?php
// Store all buffered content into content
$content = ob_get_clean();
// Include header, output the content, and include footer
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>
