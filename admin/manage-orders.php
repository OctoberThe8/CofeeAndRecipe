<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Ensure the logged-in user is an admin

$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Manage Orders";

//  Handle order status update (when admin selects a new status from the dropdown)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    // Prepare and execute the update query
    $db->query("UPDATE orders SET status = :status WHERE id = :id");
    $db->bind(":status", $_POST['status']);// Bind new status
    $db->bind(":id", $_POST['order_id']);// Bind order ID
    $db->execute();

    // Refresh page to reflect changes
    header("Location: manage-orders.php");
    exit;
}

// Fetch all orders
$db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$orders = $db->resultSet();

ob_start();// Start output buffering
?>
<h1>Manage Orders</h1>
<a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>

<!--  Orders Table -->
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

        <!--  Loop through all orders and display them -->
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order->id ?></td>
                <td><?= htmlspecialchars($order->username) ?></td>
                <td><?= date("M d, Y", strtotime($order->created_at)) ?></td>
                <td>$<?= number_format($order->total, 2) ?></td>

                <!--  Order Status Dropdown -->
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?= $order->id ?>">
                        <!-- Dropdown menu to update order status -->
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?= $order->status == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="processing" <?= $order->status == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="completed" <?= $order->status == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </form>
                </td>
                <!--  Link to view order details -->
                <td>
                    <a href="order-details.php?id=<?= $order->id ?>" class="btn btn-sm btn-edit">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Render final page with admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
