<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();

if (!isset($_GET['id'])) {
    header("Location: manage-products.php");
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch existing product
$db->query("SELECT * FROM products WHERE id = :id");
$db->bind(":id", $product_id);
$product = $db->single();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

$page_title = "Edit Product - " . htmlspecialchars($product->name);
$errors = [];
$success = "";

//  Decode existing options (if any)
$existing_options = json_decode($product->options, true) ?? ["", ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);

    //  Capture options from form
    $option1 = trim($_POST['option1']);
    $option2 = trim($_POST['option2']);
    $options_json = json_encode([$option1, $option2]);

    $new_image_name = trim($_POST['new_image_name']);

    //  Handle image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/products/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image = $_FILES['image']['name'];
    } elseif (!empty($new_image_name)) {
        $image = $new_image_name;
    } else {
        $image = $product->image_path;
    }

    //  Validate
    if (empty($name) || $price <= 0 || $stock < 0 || empty($category)) {
        $errors[] = "Please fill in all required fields correctly.";
    }

    //  Update product with options
    if (empty($errors)) {
        $db->query("UPDATE products 
                    SET name = :name, price = :price, stock = :stock, 
                        category = :category, description = :description, 
                        image_path = :image, options = :options
                    WHERE id = :id");

        $db->bind(":name", $name);
        $db->bind(":price", $price);
        $db->bind(":stock", $stock);
        $db->bind(":category", $category);
        $db->bind(":description", $description);
        $db->bind(":image", $image);
        $db->bind(":options", $options_json);
        $db->bind(":id", $product_id);
        $db->execute();

        $success = "✅ Product updated successfully!";
    }
}

ob_start();
?>

<div class="admin-container">
    <h1>Edit Product</h1>
    <a href="manage-products.php" class="btn btn-secondary">⬅ Back to Products</a>

    <?php if (!empty($errors)): ?>
        <div class="alert error"><?php foreach ($errors as $e) echo "<p>$e</p>"; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product->name) ?>" required>

        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?= $product->price ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?= $product->stock ?>" required>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($product->category) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($product->description) ?></textarea>

        <!--  Option Fields -->
        <label>Product Option 1:</label>
        <input type="text" name="option1" value="<?= htmlspecialchars($existing_options[0] ?? '') ?>">

        <label>Product Option 2:</label>
        <input type="text" name="option2" value="<?= htmlspecialchars($existing_options[1] ?? '') ?>">

        <label>Current Image Path:</label>
        <input type="text" value="<?= htmlspecialchars($product->image_path) ?>" readonly>

        <label>New Image Path (Optional):</label>
        <input type="text" name="new_image_name" placeholder="Enter new image file name">

        <p>Current Image:</p>
        <img src="../assets/images/products/<?= htmlspecialchars($product->image_path) ?>" width="100">

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
