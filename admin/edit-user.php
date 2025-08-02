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

//Redirect if no user ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: manage-users.php");
    exit;
}

$user_id = (int)$_GET['id'];// Convert ID to integer for security
$db = new Database();

//  Fetch user
$db->query("SELECT * FROM users WHERE id = :id");
$db->bind(":id", $user_id);
$user = $db->single();

//If user does not exist, show error and stop
if (!$user) {
    echo "<p>User not found.</p>";
    exit;
}

$page_title = "Edit User - " . htmlspecialchars($user->username);
$errors = [];
$success = "";

//Handle form submission when admin updates user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;// Checkbox for admin role
    $is_active = isset($_POST['is_active']) ? 1 : 0;// Checkbox for account status

// Validate required fields
    if (empty($username) || empty($email)) {
        $errors[] = "Username and Email are required.";
    }

    //  If no errors, update user in the database

    if (empty($errors)) {
        $db->query("UPDATE users 
                    SET username = :username, email = :email, is_admin = :is_admin, is_active = :is_active
                    WHERE id = :id");
        // Bind parameters
        $db->bind(":username", $username);
        $db->bind(":email", $email);
        $db->bind(":is_admin", $is_admin);
        $db->bind(":is_active", $is_active);
        $db->bind(":id", $user_id);

        // Execute update
        $db->execute();

        $success = "✅ User updated successfully!";
    }
}

ob_start();// Start output buffering
?>

<h1>Edit User</h1>
<a href="manage-users.php" class="btn btn-secondary">⬅ Back to Users</a>

<div class="form-container">
<!--  Show error messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert error"><?php foreach ($errors as $e) echo "<p>$e</p>"; ?></div>
    <?php endif; ?>
<!--  Show success message -->
    <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

<!--  Edit User Form -->
    <form method="POST" class="admin-form">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user->username) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>

        <label>
            <input type="checkbox" name="is_admin" <?= $user->is_admin ? 'checked' : '' ?>> Admin
        </label>

        <label>
            <input type="checkbox" name="is_active" <?= $user->is_active ? 'checked' : '' ?>> Active Account
        </label>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<!--  Inline CSS for form styling -->
<style>
.form-container {
  max-width: 600px;
  margin: 30px auto;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.admin-form label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

.admin-form input[type="text"],
.admin-form input[type="email"] {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.admin-form button {
  margin-top: 15px;
}
</style>

<?php
//  Render page using admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
