<?php
// Include configuration and authentication
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Create an Auth instance and check if the user is logged in
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$page_title = 'Order History';
$user_id = $_SESSION['user_id'];

// Get user's orders
$db = new Database();
$db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
$db->bind(':user_id', $user_id);
$orders = $db->resultSet();

// Start output buffering
ob_start();
?>
<section class="order-history-page">
    <h1>My Order History</h1>
    
    <?php if (!empty($orders)): ?>
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                        <!-- Order ID -->
                            <td><?php echo $order->id; ?></td>
                        <!-- Order creation date -->
                            <td><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                        <!-- Number of items in the order -->
                            <td>
                                <?php 
                                $db->query('SELECT COUNT(*) as count FROM order_items WHERE order_id = :order_id');
                                $db->bind(':order_id', $order->id);
                                echo $db->single()->count; 
                                ?> items
                            </td>
                            
                            <!-- Order total amount -->
                            <td>$<?php echo number_format($order->total, 2); ?></td>
                            
                            <!-- Order status with a badge -->
                            <td>
                                <span class="status-badge <?php echo strtolower($order->status); ?>">
                                    <?php echo ucfirst($order->status); ?>
                                </span>
                            </td>

                            <!-- Action button to view order details -->
                            <td>
                                <a href="order-details.php?id=<?php echo $order->id; ?>" class="btn btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
    <!-- Message if user has no orders -->
        <div class="empty-state">
            <i class="fas fa-shopping-bag"></i>
            <p>You haven't placed any orders yet.</p>
            <a href="../public/products.php" class="btn btn-primary">Shop Now</a>
        </div>
    <?php endif; ?>
</section>

<?php
// Store buffered content and include header/footer
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>