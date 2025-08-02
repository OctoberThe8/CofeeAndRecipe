<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'senah_han');
define('DB_PASS', 'v8MdbH4JcBKVCWJaNzHy');
define('DB_NAME', 'senah_schema');

// Site configuration
define('SITE_NAME', 'Coffee & recipe');
define('SITE_URL', 'https://senah.myweb.cs.uwindsor.ca/comp3340_project');
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include other core files
require_once 'db.php';
require_once 'auth.php';
require_once 'functions.php';
?>