<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add to cart logic
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['add_to_cart'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $options = isset($_POST['options']) ? $_POST['options'] : []; // ✅ get options

    // Get product stock
    $db = new Database();
    $db->query('SELECT stock FROM products WHERE id = :id');
    $db->bind(':id', $product_id);
    $product = $db->single();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Create a unique key for this product+options
    $key = $product_id . (!empty($options) ? '-' . md5(json_encode($options)) : '');

    //Check current quantity
    $current_qty = isset($_SESSION['cart'][$key]['quantity']) ? (int)$_SESSION['cart'][$key]['quantity'] : 0;
    $requested_qty = $current_qty + $quantity;

    // Check stock
    if ($requested_qty <= $product->stock) {
        $_SESSION['cart'][$key] = [
            'product_id' => $product_id,
            'quantity'   => $requested_qty,
            'options'    => $options // save options here
        ];
    } else {
        $_SESSION['error'] = "Not enough stock available. Only {$product->stock} remaining.";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}


// Remove item logic – supports keys with extra text
if (isset($_GET['remove'])) {
    $removeId = (string)(int)$_GET['remove']; // force integer string

    foreach ($_SESSION['cart'] as $key => $value) {
        // If key is numeric and matches OR starts with product ID (for keys with options)
        if ($key == $removeId || str_starts_with((string)$key, $removeId)) {
            unset($_SESSION['cart'][$key]);
        }
    }

    header("Location: cart.php");
    exit;
}

// Fetch products
// Fetch products based on the product_id in the session
$db = new Database();
$products = [];
$total = 0;

$ids = [];

// Collect product IDs from the new cart structure
foreach ($_SESSION['cart'] as $key => $item) {
    if (isset($item['product_id'])) {
        $ids[] = (int)$item['product_id'];
    }
}

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $db->query("SELECT * FROM products WHERE id IN ($placeholders)");

    foreach ($ids as $i => $id) {
        $db->bind($i + 1, $id);
    }

    $products = $db->resultSet();
}
?>

<h2>Your Cart</h2>
<?php if (!empty($_SESSION['cart'])): ?>
<form method="post" action="checkout.php">
    <div class="cart-container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $key => $cartItem): 
                    // Find product details for this cart item
                    $product = null;
                    foreach ($products as $p) {
                        if ($p->id == $cartItem['product_id']) {
                            $product = $p;
                            break;
                        }
                    }
                    if (!$product) continue; // Skip if product not found

                    $qty = (int)$cartItem['quantity'];
                    $subtotal = $qty * (float)$product->price;
                    $total += $subtotal;
                ?>
                <tr>
                    <td>
                    <!-- Display the product name safely using htmlspecialchars to prevent XSS -->
                        <?= htmlspecialchars($product->name) ?>
                        <?php if (!empty($cartItem['options'])): ?>
                            <br><small>
                                    <!-- Loop through product options (like size, color) and display them -->
                                <?php foreach ($cartItem['options'] as $optName => $optValue): ?>
                                    <?= htmlspecialchars($optName) ?>: <?= htmlspecialchars($optValue) ?><br>
                                <?php endforeach; ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <!-- Display the quantity of the current product in the cart -->
                    <td><?= $qty ?></td>
                    <!-- Display the product price formatted to 2 decimal places -->
                    <td>$<?= number_format((float)$product->price, 2) ?></td>
                    <!-- Display the subtotal for this product (price * quantity) -->
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <!-- Link to remove this product from the cart using its key -->
                        <a href="cart.php?remove=<?= urlencode($key) ?>" 
                           class="remove-link" 
                           style="color:red;font-weight:bold;">
                            Remove
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-total">
            <h3>Total: $<?= number_format($total, 2) ?></h3>
                <!-- Button to proceed to checkout -->
            <input type="submit" value="Proceed to Checkout" class="btn btn-primary">
        </div>
    </div>
</form>
<?php else: ?>

<p>Your cart is empty.</p>
<?php endif; ?>

<?php
// Get all buffered content and store it in $content
$content = ob_get_clean();

// Include the page header
include '../includes/header.php';

// Output the main page content
echo $content;

// Include the page footer
include '../includes/footer.php';
?>
