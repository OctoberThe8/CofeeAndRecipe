<?php
session_start();
// Include configuration and database connection
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

// Ensure a favorite_id is provided in the request
if (!isset($_POST['favorite_id'])) {
    echo "error";
    exit;
}

$db = new Database();

// Prepare and execute the DELETE query to remove the favorite
$db->query("DELETE FROM favorites WHERE id = :id AND user_id = :user_id");
$db->bind(":id", $_POST['favorite_id']);
$db->bind(":user_id", $_SESSION['user_id']);

// If deletion is successful, return "removed", otherwise return "error"
if ($db->execute()) {
    echo "removed";
} else {
    echo "error";
}
?>
