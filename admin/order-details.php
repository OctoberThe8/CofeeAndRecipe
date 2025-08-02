<?php
// Start session to manage authentication
session_start();
// Include configuration, authentication, and database connection files
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

//  Check if the current user is logged in and has admin privileges
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    // Redirect non-admin users to login page
    header("Location: ../public/login.php");
    exit;
}

// Ensure an order ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect to manage orders page if no ID is passed
    header("Location: manage-orders.php");
    exit;
}

// Convert order ID to integer for security
$order_id = (int)$_GET['id'];

// Create a new database instance
$db = new Database();

//  Fetch order details along with the associated user's information
$db->query("SELECT o.*, u.username, u.email FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = :id");
$db->bind(":id", $order_id);
$order = $db->single();

//  If the order does not exist, display an error message
if (!$order) {
    echo "<p>Order not found.</p>";
    exit;
}

//  Fetch order items
$db->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :id");
$db->bind(":id", $order_id);
$items = $db->resultSet();

$page_title = "Order #$order_id";

//  Start output buffering
ob_start();
?>
<div class="order-details-page">
  <a href="manage-orders.php" class="btn btn-secondary">⬅ Back to Orders</a>
  
  <!-- Display order number -->
  <h1>Order #<?= $order_id ?></h1>

  <!-- Order and Customer Info -->
  <div class="order-info-grid">
  <div class="info-card">
    <h3>Customer Info</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($order->username) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order->email) ?></p>
  </div>

  <div class="info-card">
    <h3>Order Details</h3>
    <p><strong>Status:</strong> <?= ucfirst($order->status) ?></p>
    <p><strong>Total:</strong> $<?= number_format($order->total, 2) ?></p>
    <p><strong>Date:</strong> <?= date("M d, Y H:i", strtotime($order->created_at)) ?></p>
  </div>
</div>

<!-- Table displaying order items -->
  <h2>Items</h2>
  <table class="admin-table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item->name) ?></td>
          <td>$<?= number_format($item->price, 2) ?></td>
          <td><?= $item->quantity ?></td>
          <td>$<?= number_format($item->price * $item->quantity, 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php
// Store page content and include admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>

