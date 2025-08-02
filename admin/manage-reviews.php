<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

//  Check if user is logged in and is an admin
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Manage Reviews";

//  Delete review
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $db->query("DELETE FROM reviews WHERE id = :id");
    $db->bind(":id", $delete_id);
    $db->execute();
    header("Location: manage-reviews.php");
    exit;
}

//  Fetch all reviews
$db->query("SELECT r.id, r.rating, r.comment, r.created_at, u.username,
            CASE 
                WHEN r.product_id IS NOT NULL THEN 'Product' 
                ELSE 'Recipe' 
            END as type
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC");
$reviews = $db->resultSet();

ob_start();// Start output buffering
?>
<h1>Manage Reviews</h1>
<a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>

<!--  Display all reviews in a table -->
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= $review->id ?></td>
                <td><?= htmlspecialchars($review->username) ?></td>
                <td><?= $review->type ?></td>
                <td><?= $review->rating ?> ★</td>
                <td><?= htmlspecialchars($review->comment) ?></td>
                <td><?= date("M d, Y", strtotime($review->created_at)) ?></td>
                <td>
                <!--  Delete review form -->
                    <form method="POST" onsubmit="return confirm('Delete this review?');">
                        <input type="hidden" name="delete_id" value="<?= $review->id ?>">
                        <button type="submit" class="btn btn-sm btn-delete">🗑 Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
//  Render final page inside admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
