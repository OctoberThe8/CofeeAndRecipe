<?php
// Start session and include required files
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$auth = new Auth();

// Redirect if user is not logged in or not an admin
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Add New Product";

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $options = json_encode([$option1, $option2]); //  Save as JSON

    $image_path = trim($_POST['image']); // Manual image file name

    if (empty($name) || $price <= 0 || $stock < 0 || empty($category)) {
        $errors[] = "Please fill in all required fields correctly.";
    }

    // If no errors, insert new product into database
    if (empty($errors)) {
        $db->query("INSERT INTO products (name, price, stock, category, description, image_path, options) 
                    VALUES (:name, :price, :stock, :category, :description, :image_path, :options)");

        $db->bind(":name", $name);
        $db->bind(":price", $price);
        $db->bind(":stock", $stock);
        $db->bind(":category", $category);
        $db->bind(":description", $description);
        $db->bind(":image_path", $image_path);
        $db->bind(":options", $options);

        $db->execute();
        $success = "✅ Product added successfully!";
    }
}

ob_start();
?>

<div class="admin-container">
    <h1>Add New Product</h1>
    <a href="manage-products.php" class="btn btn-secondary">⬅ Back to Products</a>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>
<!-- Display success message -->
    <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
<!-- Form to add a new product -->
    <form method="POST" class="admin-form">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Category:</label>
        <input type="text" name="category" required>

        <label>Description:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Product Option 1:</label>
        <input type="text" name="option1" placeholder="e.g., Medium Roast" required>

        <label>Product Option 2:</label>
        <input type="text" name="option2" placeholder="e.g., Dark Roast" required>

        <label>Image File Name:</label>
        <input type="text" name="image" placeholder="e.g., French Press.jpg" required>
        <small><strong>Note:</strong> The image must be placed inside <code>assets/images/products/</code>. Example: <code>French Press.jpg</code></small>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<?php
$content = ob_get_clean();
// Output the content and include admin header/footer
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
