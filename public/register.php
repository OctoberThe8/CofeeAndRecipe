<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

$db = new Database();
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    //  Check if passwords match
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        //  Check if username already exists
        $db->query("SELECT id FROM users WHERE username = :username");
        $db->bind(':username', $username);
        $existing_user = $db->single();

        if ($existing_user) {
            $error = "This username is already taken. Please choose another.";
        } else {
            //  Check if email already exists
            $db->query("SELECT id FROM users WHERE email = :email");
            $db->bind(':email', $email);
            $existing_email = $db->single();

            if ($existing_email) {
                $error = "This email is already registered. Please use another.";
            } else {
                //  Insert new user if everything is valid
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $db->bind(':username', $username);
                $db->bind(':email', $email);
                $db->bind(':password', $hashed);

                if ($db->execute()) {
                    $success = "✅ Registration successful. <a href='login.php'>Login here</a>.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Coffee & Recipes</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        .form-container { max-width: 400px; margin: auto; padding: 2em; }
        input, button { width: 100%; padding: 0.7em; margin-top: 0.5em; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
        <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
        <form method="POST" action="">
            <input type="username" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
