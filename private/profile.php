<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$auth = new Auth();

// ✅ Redirect if user is not logged in
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$page_title = 'My Profile';
$user_id = $_SESSION['user_id'];
$db = new Database();

$success = "";
$error = "";

// ✅ Handle profile update when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $password   = trim($_POST['password'] ?? ''); // New password input

    // ✅ Validate required fields
    if (empty($username) || empty($email)) {
        $error = "Username and email cannot be empty.";
    } else {
        if (!empty($password)) {
            // ✅ If password is provided, hash it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $db->query("UPDATE users 
                        SET username = :username, email = :email,
                            first_name = :first_name, last_name = :last_name,
                            address = :address, phone = :phone,
                            password = :password, updated_at = NOW()
                        WHERE id = :id");
            $db->bind(':password', $hashed_password);
        } else {
            // ✅ If password is empty, update profile without changing password
            $db->query("UPDATE users 
                        SET username = :username, email = :email,
                            first_name = :first_name, last_name = :last_name,
                            address = :address, phone = :phone,
                            updated_at = NOW()
                        WHERE id = :id");
        }

        // Bind common fields
        $db->bind(':username', $username);
        $db->bind(':email', $email);
        $db->bind(':first_name', $first_name);
        $db->bind(':last_name', $last_name);
        $db->bind(':address', $address);
        $db->bind(':phone', $phone);
        $db->bind(':id', $user_id);

        $db->execute();
        $success = "✅ Profile updated successfully!";
    }
}

// ✅ Fetch updated user data
$db->query('SELECT * FROM users WHERE id = :id');
$db->bind(':id', $user_id);
$user = $db->single();

// ✅ Fetch recent orders
$db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5');
$db->bind(':user_id', $user_id);
$orders = $db->resultSet();

ob_start();
?>
<section class="profile-page">
    <div class="profile-header">
        <div class="avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <h1><?= htmlspecialchars($user->username) ?></h1>
            <p>Member since <?= date('F Y', strtotime($user->created_at)) ?></p>
        </div>
    </div>

    <!-- ✅ Tabs for switching between sections -->
    <div class="profile-tabs">
        <button class="tab-btn active" data-tab="details">Personal Details</button>
        <button class="tab-btn" data-tab="orders">Recent Orders</button>
        <button class="tab-btn" data-tab="favorites">Favorites</button>
    </div>

    <!-- ✅ Personal Details Tab -->
    <div class="tab-content active" id="details">
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color:green;"><?= $success ?></p>
        <?php endif; ?>

        <form class="profile-form" method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user->username ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user->email ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user->first_name ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user->last_name ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?= htmlspecialchars($user->address ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>">
            </div>

            <!-- ✅ Secure Password Update -->
            <div class="form-group">
                <label>New Password (Leave blank to keep current password)</label>
                <input type="password" name="password" placeholder="Enter new password">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- ✅ Recent Orders Tab -->
    <div class="tab-content" id="orders">
        <?php if (!empty($orders)): ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <hr>
                        <p>Your Current and Past Orders</p>
                        <div class="order-header">
                            <span>Order #<?= $order->id ?></span>
                            <span><?= date('M d, Y', strtotime($order->created_at)) ?></span>
                            <span class="status <?= strtolower($order->status) ?>"><?= $order->status ?></span>
                        </div>
                        <div class="order-total">Total: $<?= number_format($order->total, 2) ?></div>
                        <a href="order-details.php?id=<?= $order->id ?>">View Details</a>
                        <hr>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>You haven't placed any orders yet.</p>
        <?php endif; ?>
    </div>

    <!-- ✅ Favorites Tab -->
    <div class="tab-content" id="favorites">
        <p>Your saved favorites will appear here.</p>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));

            tab.classList.add("active");
            document.getElementById(tab.dataset.tab).classList.add("active");
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>
