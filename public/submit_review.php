<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);
    $item_type = $_POST['item_type']; // 'recipe' or 'product'
    $item_id = $_POST['item_id'];     // recipe_id or product_id depending on item_type

    $db = new Database();
    // Insert a review into the database based on the item type
    if ($item_type === 'recipe') {
        $db->query("INSERT INTO reviews (user_id, recipe_id, item_type, rating, comment, created_at) 
                    VALUES (:user_id, :item_id, :item_type, :rating, :comment, NOW())");
    } elseif ($item_type === 'product') {
        $db->query("INSERT INTO reviews (user_id, product_id, item_type, rating, comment, created_at) 
                    VALUES (:user_id, :item_id, :item_type, :rating, :comment, NOW())");
    } else {
        // Invalid item_type submitted
        header("Location: error.php");
        exit;
    }
    // Bind the parameters for the prepared statement
    $db->bind(':user_id', $user_id);
    $db->bind(':item_id', $item_id);
    $db->bind(':item_type', $item_type);
    $db->bind(':rating', $rating);
    $db->bind(':comment', $comment);
    $db->execute();

    // Redirect back to the appropriate page
    if ($item_type === 'recipe') {
        header("Location: recipe-detail.php?id=$item_id");
    } else {
        header("Location: product-detail.php?id=$item_id");
    }
    exit;
}
?>
