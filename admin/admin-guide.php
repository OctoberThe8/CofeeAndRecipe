<?php
// Start session and include required files
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

$auth = new Auth();

// Restrict access to admins only
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$page_title = "Admin Guide";

ob_start();
?>

<div class="admin-container">
    <h1>📖 Admin User Guide</h1>
    <p>Welcome to the <strong>Coffee & Recipes Admin Panel</strong>. This guide explains how to manage products, recipes, users, and site settings.</p>

    <hr>
    <!-- Guide Sections -->

    <h2>🔑 1. Logging In</h2>
    <p>Go to the <a href="<?= SITE_URL; ?>/public/login.php">login page</a> and sign in with your admin account. After login, you will see the <strong>Admin Dashboard</strong> which is this now.</p>

    <h2>📊 2. Dashboard Overview</h2>
    <ul>
        <li>Shows total users, products, orders, and revenue.</li>
        <li>Displays monthly revenue chart.</li>
        <li>Lists recent orders and reviews for quick management.</li>
    </ul>

    <h2>🛒 3. Managing Products</h2>
    <ul>
        <li><strong>Add Product:</strong> Go to <a href="manage-products.php">Manage Products</a> → <a href="add-product.php">Add New Product</a>.</li>
        <li><strong>Edit Product:</strong> On <a href="manage-products.php">Manage Products</a>, click <em>Edit</em> to update details.</li>
        <li><strong>Delete Product:</strong> Click <em>Delete</em> to remove a product permanently.</li>
    </ul>

    <h2>📖 4. Managing Recipes</h2>
    <ul>
        <li><strong>Add Recipe:</strong> Go to <a href="manage-recipes.php">Manage Recipes</a> → <a href="add-recipe.php">Add New Recipe</a>.</li>
        <li><strong>Edit Recipe:</strong> Click <em>Edit</em> to update details.</li>
        <li><strong>Delete Recipe:</strong> Click <em>Delete</em> to remove a recipe.</li>
    </ul>

    <h2>👥 5. Managing Users</h2>
    <ul>
        <li><strong>Edit User:</strong> Go to <a href="manage-users.php">Manage Users</a> → <em>Edit</em>. You can update username, email, role (admin/user), and account status.</li>
        <li><strong>Disable User:</strong> Uncheck <em>Active Account</em> to disable login for that user.</li>
        <li><strong>Delete User:</strong> Click <em>Delete</em> to remove a user completely.</li>
    </ul>

    <h2>🎨 6. Changing Site Theme</h2>
    <ul>
        <li>Go to <a href="theme-settings.php">Theme Settings</a>.</li>
        <li>Select one of the available themes: <strong>Regular, Christmas, Halloween, Easter</strong>.</li>
    </ul>

    <h2>📦 7. Viewing Orders</h2>
    <p>From the <a href="manage-orders.php">Orders</a>, you can see all orders. Click <em>Dashboard </em> to see recent order .</p>

    <h2>⭐ 8. Managing Reviews</h2>
    <p>See recent reviews in the <a href="manage-reviews.php">Reviews</a>. You can remove inappropriate reviews if needed.</p>

    <h2>🚪 9. Logging Out</h2>
    <p>Click <strong>Logout</strong> in the top navigation menu to securely log out.</p>

    <hr>
    <p><strong>Tip:</strong> Always verify changes after saving, especially for products, recipes, and user accounts.By going back to the main page and click again on eidt to see the changes.</p>
</div>

<!-- Page-specific styles -->
<style>
.admin-container {
    max-width: 900px;
    margin: 20px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    line-height: 1.6;
}
.admin-container h1 {
    margin-bottom: 10px;
}
.admin-container h2 {
    margin-top: 20px;
    color: #4a6fa5;
}
.admin-container ul {
    margin-left: 20px;
}
.admin-container a {
    color: #4a6fa5;
    font-weight: bold;
    text-decoration: none;
}
.admin-container a:hover {
    text-decoration: underline;
}
</style>

<?php
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
