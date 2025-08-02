<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
// Create a new database instance
$db = new Database();
// Variable to store error messages
$error = "";
// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the email and password from the submitted form (use null coalescing to avoid undefined index errors)
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare a query to find a user by email
    $db->query("SELECT * FROM users WHERE email = :email");
    $db->bind(':email', $email);
    $user = $db->single();

    // If a user is found and the password matches the hashed password in the database
    if ($user && password_verify($password, $user->password)) {
        // Store user information in session variables
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->email;
        $_SESSION['is_admin'] = $user->is_admin;

        // Redirect user to the admin dashboard if they are an admin, otherwise to the homepage
        if ($user->is_admin == 1) {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../public/index.php");
        }

        exit; //Always exit after redirect
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Coffee & Recipes</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .form-container { max-width: 400px; margin: auto; padding: 2em; }
        input, button { width: 100%; padding: 0.7em; margin-top: 0.5em; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <!-- Display error message if login fails -->
        <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
        <!-- Login form -->
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
                <!-- Link to registration page -->
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
