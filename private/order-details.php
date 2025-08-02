<?php
session_start();
require_once '../includes/db.php';

// Check if an order ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<p>Invalid Order</p>";
    exit;
}

$order_id = (int)$_GET['id'];

$db = new Database();

// Fetch order
$db->query("SELECT * FROM orders WHERE id = :id");
$db->bind(':id', $order_id);
$order = $db->single();

if (!$order) {
    echo "<p>Order not found</p>";
    exit;
}

// Fetch order items
$db->query("SELECT * FROM order_items WHERE order_id = :id");
$db->bind(':id', $order_id);
$order_items = $db->resultSet();

// Fetch product names for each item
$productIds = array_map(fn($item) => $item->product_id, $order_items);
$products = [];

// If there are products in the order, fetch their details
if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $db->query("SELECT * FROM products WHERE id IN ($placeholders)");
    foreach ($productIds as $i => $id) {
        $db->bind($i + 1, $id);
    }
    $productResults = $db->resultSet();
    foreach ($productResults as $p) {
        $products[$p->id] = $p;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order->id ?> Details</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #fff; }
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #000; }
        .back-link { display: inline-block; margin-top: 20px; padding: 8px 14px; background: #4a6fa5; color: white; text-decoration: none; border-radius: 4px; }
        .back-link:hover { background: #3a5985; }
    </style>
</head><!-- the end of the head-->
<body>

<h1>Order #<?= $order->id ?></h1>
<p><strong>Status:</strong> <?= htmlspecialchars($order->status) ?></p>
<p><strong>Date:</strong> <?= date('M d, Y', strtotime($order->created_at)) ?></p>
<p><strong>Total:</strong> $<?= number_format($order->total, 2) ?></p>

<h2>Items</h2>
<table>
    <tr>
        <th>Product</th>
        <th>Options</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    <!-- Loop through each order item -->
    <?php foreach ($order_items as $item): 
        $productName = $products[$item->product_id]->name ?? "Product #{$item->product_id}";
        $subtotal = $item->quantity * $item->price;

        // If you have an "options" column in order_items, decode it
        $options = "";
        if (!empty($item->options)) {
            $decoded = json_decode($item->options, true);
            if (is_array($decoded)) {
                foreach ($decoded as $key => $val) {
                    $options .= htmlspecialchars($key) . ": " . htmlspecialchars($val) . "<br>";
                }
            }
        }
    ?>
    <tr>
        <td><?= htmlspecialchars($productName) ?></td>
        <td><?= $options ?: '-' ?></td>
        <td><?= (int)$item->quantity ?></td>
        <td>$<?= number_format($item->price, 2) ?></td>
        <td>$<?= number_format($subtotal, 2) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<!-- Links to go back to profile or order history -->
<a href="profile.php" class="back-link">← Back to Profile</a>
<a href="order-history.php" class="back-link">← Back to Orders</a>


</body>
</html>
<?php
// Capture the output and include the site layout
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>