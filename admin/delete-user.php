<?php
// Start the session to access session variables
session_start();
require_once '../includes/db.php';

// Check if the user is an admin; if not, deny access
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo "Unauthorized.";
    exit;
}

// Only allow POST requests for this operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data and ensure it is an integer
    $user_id = intval($_POST['user_id'] ?? 0);

    // Proceed only if a valid user ID is provided
    if ($user_id > 0) {
        $db = new Database();

        // Prepare and execute a DELETE query to remove the user
        $db->query("DELETE FROM users WHERE id = :id");
        $db->bind(':id', $user_id);
        $db->execute();
        
        // Redirect back to the users list page with a success status
        header('Location: users-list.php?status=deleted');
        exit;
    } else {
        // If the user ID is invalid, display an error message
        echo "Invalid user ID.";
    }
} else {
    // If the request method is not POST, return a 405 Method Not Allowed error
    http_response_code(405);
    echo "Method not allowed.";
}
