<?php

// Start session and include required files
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$auth = new Auth();

// Restrict access to logged-in admins only
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

// Check if a theme is provided in the URL
if (isset($_GET['theme'])) {
    $allowed_themes = ['halloween', 'regular', 'Christmas', 'easter'];
    $theme = in_array($_GET['theme'], $allowed_themes) ? $_GET['theme'] : 'regular';

    setTheme($theme);
    setcookie('theme_preference', $theme, time() + (86400 * 30), "/");
}

//Always return to admin dashboard
header("Location: " . SITE_URL . "/admin/dashboard.php");
exit;
