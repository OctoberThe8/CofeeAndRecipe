<?php
// Start session and include required files
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

$auth = new Auth();
// Restrict access to logged-in admins only
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Add New Recipe";
$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $prep_time = trim($_POST['prep_time']);
    $cook_time = trim($_POST['cook_time']);
    $servings = trim($_POST['servings']);
    $difficulty = trim($_POST['difficulty']);
    $category = trim($_POST['category']);
    $image_path = trim($_POST['image_path']); // Use the filename directly
    
    // Validate required fields
    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description are required.";
    }
    // If no errors, insert recipe into database
    if (empty($errors)) {
        $db->query("INSERT INTO recipes 
            (title, description, ingredients, instructions, prep_time, cook_time, servings, difficulty, category, image_path, created_at) 
            VALUES (:title, :description, :ingredients, :instructions, :prep_time, :cook_time, :servings, :difficulty, :category, :image_path, NOW())");

        $db->bind(":title", $title);
        $db->bind(":description", $description);
        $db->bind(":ingredients", $ingredients);
        $db->bind(":instructions", $instructions);
        $db->bind(":prep_time", $prep_time);
        $db->bind(":cook_time", $cook_time);
        $db->bind(":servings", $servings);
        $db->bind(":difficulty", $difficulty);
        $db->bind(":category", $category);
        $db->bind(":image_path", $image_path);

        $db->execute();
        $success = "✅ Recipe added successfully!";
    }
}

ob_start();
?>

<div class="admin-container">
    <h1>Add New Recipe</h1>
    <a href="manage-recipes.php" class="btn btn-secondary">⬅ Back to Recipes</a>
<!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert error"><?php foreach ($errors as $e) echo "<p>$e</p>"; ?></div>
    <?php endif; ?>
<!-- Display success message -->
    <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
<!-- Recipe creation form -->
    <form method="POST" class="admin-form">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Ingredients:</label>
        <textarea name="ingredients" required></textarea>

        <label>Instructions:</label>
        <textarea name="instructions" required></textarea>

        <label>Prep Time (mins):</label>
        <input type="number" name="prep_time">

        <label>Cook Time (mins):</label>
        <input type="number" name="cook_time">

        <label>Servings:</label>
        <input type="number" name="servings">

        <label>Difficulty:</label>
        <select name="difficulty">
            <option value="Easy">Easy</option>
            <option value="Medium">Medium</option>
            <option value="Hard">Hard</option>
        </select>

        <label>Category:</label>
        <input type="text" name="category">

        <label>Image Filename (e.g., tiramisu.jpg):</label>
        <input type="text" name="image_path" placeholder="tiramisu.jpg" required>
        <small style="display:block; color:#6F4E37; margin-top:5px;">
            📌 Note: The image must be placed inside <strong>assets/images/recipes/</strong>.
        </small>

        <button type="submit" class="btn btn-primary">Add Recipe</button>
    </form>
</div>

<?php
// Render the page with admin header and footer
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
