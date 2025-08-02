<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

//  Ensure only logged-in admins can access this page
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Manage Products";

// Handle Delete
if (isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    
    // Delete product from database
    $db->query("DELETE FROM products WHERE id = :id");
    $db->bind(":id", $product_id);
    $db->execute();
    // Redirect to refresh the page after deletion
    header("Location: manage-products.php");
    exit;
}

//  Fetch all products
$db->query("SELECT * FROM products ORDER BY id DESC");
$products = $db->resultSet();

ob_start();// Start output buffering
?>
<h1>Manage Products</h1>
<!--  Action buttons for adding a product or going back -->
<div class="action-buttons">
  <a href="add-product.php" class="btn-small">➕ Add New Product</a>
  <a href="dashboard.php" class="btn-small">⬅ Back</a>
</div>

<!--  Products Table -->
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($products): ?>
            <!--  Loop through each product and display its details -->
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product->id ?></td>
                    <td><?= htmlspecialchars($product->name) ?></td>
                    <td>$<?= number_format($product->price, 2) ?></td>
                    <td><?= $product->stock ?></td>
                    <td><?= htmlspecialchars($product->category) ?></td>
                    <td>
                    <!-- Edit Product Button -->
                        <a href="edit-product.php?id=<?= $product->id ?>" class="btn btn-sm btn-edit">✏ Edit</a>
                    <!-- Delete Product Button (asks for confirmation) -->
                        <a href="manage-products.php?delete=<?= $product->id ?>" 
                           onclick="return confirm('Are you sure you want to delete this product?')"
                           class="btn btn-sm btn-delete">🗑 Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- If no products exist -->
            <tr><td colspan="6">No products found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
//  Render final page with admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
