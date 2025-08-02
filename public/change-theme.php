<?php
// Start a new session or resume the existing one
session_start();

// Include configuration and helper functions
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if the 'theme' parameter is provided in the URL
if (isset($_GET['theme'])) {
    // Define a list of themes that are allowed
    $allowed_themes = ['halloween', 'regular', 'Christmas', 'easter'];

    // If the requested theme is in the allowed list, use it; otherwise default to 'regular'
    $theme = in_array($_GET['theme'], $allowed_themes) ? $_GET['theme'] : 'regular';

    // Call a function to set the chosen theme (likely saves in session or similar)
    setTheme($theme);

    // Store the selected theme in a cookie for 30 days so it persists between visits
    setcookie('theme_preference', $theme, time() + (86400 * 30), "/");
}

// Redirect the user back to the homepage after setting the theme
header("Location: " . SITE_URL . "/public/index.php");
exit;
