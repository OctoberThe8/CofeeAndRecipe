<?php

// Start a session for authentication
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if user is logged in and is an admin
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Admin Dashboard";

// Fetch total number of users
$db->query("SELECT COUNT(*) as total FROM users");
$users_count = $db->single()->total ?? 0;

// Fetch total number of products
$db->query("SELECT COUNT(*) as total FROM products");
$products_count = $db->single()->total ?? 0;

// Fetch total number of orders
$db->query("SELECT COUNT(*) as total FROM orders");
$orders_count = $db->single()->total ?? 0;

// Fetch total revenue
$db->query("SELECT SUM(total) as revenue FROM orders");
$revenue = $db->single()->revenue ?? 0;


// Fetch monthly revenue
$db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as monthly_revenue 
            FROM orders 
            GROUP BY month 
            ORDER BY month ASC");
$monthly_data = $db->resultSet();

$months = [];
$revenues = [];

// Prepare arrays for chart labels and data
foreach ($monthly_data as $data) {
    $months[] = $data->month;
    $revenues[] = $data->monthly_revenue;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">☕ Dashboard</a></li>
            <li><a href="manage-products.php">🥐 Manage Products</a></li>
            <li><a href="manage-recipes.php">🍰 Manage Recipes</a></li>
            <li><a href="manage-users.php">👥 Manage Users</a></li>
            <li><a href="manage-orders.php">🛒 Orders</a></li>
            <li><a href="manage-reviews.php">⭐ Reviews</a></li>
            <li><a href="theme-settings.php">🎨 Theme</a></li>
            <li><a href="status.php">📊 Website Status</a></li>
            <li><a href="admin-guide.php">📖 Admin Guide</a></li>
            <li><a href="../public/logout.php">🚪 Logout</a></li>

        </ul>
    </aside>

    <!-- Main Content -->
    <div class="admin-content">
        <header class="admin-header">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h1>
            <nav>
                <a href="../public/index.php">🏠 View Site</a>
            </nav>
        </header>

        <!-- Stats -->
        <div class="admin-stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-number"><?= $users_count ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Products</h3>
                <div class="stat-number"><?= $products_count ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <div class="stat-number"><?= $orders_count ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="stat-number">$<?= number_format($revenue, 2) ?></div>
            </div>
        </div>
        <h2>Revenue Overview</h2>
        <canvas id="revenueChart" style="max-width: 600px; margin-bottom: 30px;"></canvas>

        <!-- Recent Orders Table -->
        <h2>Recent Orders</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $db->query("SELECT o.id, o.total, o.status, o.created_at, u.username 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC LIMIT 5");
            $orders = $db->resultSet();
            foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order->id ?></td>
                    <td><?= htmlspecialchars($order->username) ?></td>
                    <td><?= date("M d, Y", strtotime($order->created_at)) ?></td>
                    <td>$<?= number_format($order->total, 2) ?></td>
                    <td><?= ucfirst($order->status) ?></td>
                    <td><a href="order-details.php?id=<?= $order->id ?>" class="btn btn-sm btn-edit">View</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Recent Reviews Table -->
        <h2>Recent Reviews</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $db->query("SELECT r.id, r.rating, r.created_at, u.username, 
                        CASE WHEN r.product_id IS NOT NULL THEN 'Product' ELSE 'Recipe' END as type 
                        FROM reviews r 
                        JOIN users u ON r.user_id = u.id 
                        ORDER BY r.created_at DESC LIMIT 5");
            $reviews = $db->resultSet();
            foreach ($reviews as $review): ?>
                <tr>
                    <td><?= $review->id ?></td>
                    <td><?= htmlspecialchars($review->username) ?></td>
                    <td><?= $review->rating ?>⭐</td>
                    <td><?= $review->type ?></td>
                    <td><?= date("M d, Y", strtotime($review->created_at)) ?></td>
                    <td><a href="#" class="btn btn-sm btn-delete">Delete</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar', // or 'line'
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Monthly Revenue ($)',
            data: <?= json_encode($revenues) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
