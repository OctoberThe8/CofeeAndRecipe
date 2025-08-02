<?php
// Start session to manage user authentication
session_start();
// Include configuration, authentication, and database connection files
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

//  Check if the current user is logged in and has admin privileges
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

//  Create a new database instance
$db = new Database();
$page_title = "Manage Users";

//  Handle delete user
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $db->query("DELETE FROM users WHERE id = :id");
    $db->bind(":id", $delete_id);
    $db->execute();
    
    // Redirect to refresh the page after deletion
    header("Location: manage-users.php");
    exit;
}

//  Fetch all users from the database, ordered by creation date (newest first)
$db->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $db->resultSet(); // Fetch all users as an array of objects

ob_start();// Start output buffering to store the HTML content
?>
<h1>Manage Users</h1>
<a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>

<!--  Display all users in a table -->
<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= htmlspecialchars($user->username) ?></td>
                <td><?= htmlspecialchars($user->email) ?></td>
                <td><?= $user->is_admin ? "Admin" : "User" ?></td>
                <td><?= $user->is_active ? "Active" : "Disabled" ?></td>
                
                <!--  Edit and Delete actions -->
                <td>
                    <a href="edit-user.php?id=<?= $user->id ?>" class="btn btn-sm btn-edit">Edit</a>
                    <a href="?delete=<?= $user->id ?>" class="btn btn-sm btn-delete" onclick="return confirm('Delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Store the content and include admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
