<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include required files
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$auth = new Auth();

// Restrict access to logged-in admins
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Set page title dynamically -->
    <title><?= isset($page_title) ? $page_title . ' | Admin Panel' : 'Admin Panel'; ?></title>
    <meta name="description" content="Admin dashboard for managing Coffee & Recipes website.">

    <!-- Load Theme -->
    <link rel="stylesheet" href="<?= SITE_URL; ?>/assets/css/theme-regular.css">
    <link rel="stylesheet" href="<?= SITE_URL; ?>/assets/css/theme-<?= getCurrentTheme(); ?>.css">
    <!-- Dynamic theme styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= SITE_URL; ?>/assets/css/admin.css"> <!-- ✅ You can style admin layout in this file -->
</head>
<body class="<?= getCurrentTheme(); ?>">

<div class="admin-header">
    <h1>☕ Admin Panel</h1>
    <nav class="admin-nav">
    <!--links to the pages that the admin will work on it-->
    <ul>

        <a href="<?= SITE_URL; ?>/admin/dashboard.php">Dashboard</a>
        <a href="<?= SITE_URL; ?>/admin/manage-users.php">Users</a>
        <a href="<?= SITE_URL; ?>/admin/manage-products.php">Products</a>
        <a href="<?= SITE_URL; ?>/admin/manage-recipes.php">Recipes</a>
        <a href="<?= SITE_URL; ?>/admin/manage-orders.php">Orders</a>
        <a href="<?= SITE_URL; ?>/public/logout.php">Logout</a>
    </ul>
    </nav>
</div>

<div class="admin-layout">
