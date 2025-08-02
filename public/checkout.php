<?php
session_start();
require_once '../includes/db.php';

ob_start();
include '../includes/header.php';

$db = new Database();

// Redirect to cart if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='products.php'>Go shopping</a></p>";
    include '../includes/footer.php';
    exit;
}

// Calculate total
$total = 0;
//Get all product IDs from the new cart structure
$productIds = [];
foreach ($_SESSION['cart'] as $item) {
    $productIds[] = (int)$item['product_id'];
}

if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $db->query("SELECT * FROM products WHERE id IN ($placeholders)");
    // Bind each product ID to its placeholder
    foreach ($productIds as $i => $id) {
        $db->bind($i + 1, $id);
    }

    $products = $db->resultSet();
} else {
    $products = [];
}
// Calculate the total price of the cart
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    foreach ($products as $p) {
        if ($p->id == $item['product_id']) {
            $total += $p->price * $item['quantity'];
            break;
        }
    }
}

?>

<h2>Checkout</h2>
<!-- Checkout form where user enters their details -->
<form method="post" action="process_payment.php">
    <label>Name: <input type="text" name="name" required></label><br><br>
    <label>Email: <input type="email" name="email" required></label><br><br>
    <label>Shipping_Address: <input type="text" name="shipping_address" required></label><br><br>
    <label>Card Number: <input type="text" name="card_number" required></label><br><br>
    <!-- Hidden input to send the total amount to the payment processing page -->
    <input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>">

<?php if (isset($_SESSION['user_id'])): ?>
    <!-- If user is logged in, show the confirm and pay button -->
    <button type="submit">Confirm & Pay $<?php echo number_format($total, 2); ?></button>
<?php else: ?>
    <!-- If user is not logged in, ask them to log in before checkout -->
    <p class="error">You must be logged in to complete your purchase.</p>
    <a href="login.php" class="btn btn-warning">Login to Checkout</a>
<?php endif; ?>
</form>

<?php
// Include the page footer
include '../includes/footer.php';
?>
