<?php
session_start();
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}
// Ensure a recipe_id is provided in the request
if (!isset($_POST['recipe_id'])) {
    echo "no_id";
    exit;
}

$user_id = $_SESSION['user_id'];
$recipe_id = (int)$_POST['recipe_id'];

$db = new Database();
// Check if the recipe is already in the user's favorites
$db->query("SELECT id FROM favorites WHERE user_id = :uid AND recipe_id = :rid");
$db->bind(':uid', $user_id);
$db->bind(':rid', $recipe_id);
$fav = $db->single();

if ($fav) {
    // Remove from favorites
    $db->query("DELETE FROM favorites WHERE id = :id");
    $db->bind(':id', $fav->id);
    $db->execute();
    echo "removed";
} else {
    // Add to favorites
    $db->query("INSERT INTO favorites (user_id, recipe_id) VALUES (:uid, :rid)");
    $db->bind(':uid', $user_id);
    $db->bind(':rid', $recipe_id);
    $db->execute();
    echo "added";
}
