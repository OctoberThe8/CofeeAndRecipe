<?php
//  Start session and include required files
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

//  Check if user is logged in and is an admin
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

//  Redirect back if no recipe ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage-recipes.php");
    exit;
}

$recipe_id = (int)$_GET['id'];
$db = new Database();

//  Fetch the existing recipe
$db->query("SELECT * FROM recipes WHERE id = :id");
$db->bind(":id", $recipe_id);
$recipe = $db->single();

if (!$recipe) {
    echo "<p>Recipe not found.</p>";
    exit;
}

$page_title = "Edit Recipe - " . htmlspecialchars($recipe->title);
$errors = [];
$success = "";

//  Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔹 Capture form input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $prep_time = trim($_POST['prep_time']);
    $cook_time = trim($_POST['cook_time']);
    $servings = trim($_POST['servings']);
    $difficulty = trim($_POST['difficulty']);
    $category = trim($_POST['category']);
    $new_image_name = trim($_POST['new_image_name'] ?? ""); // Manual image filename

    //  Handle image: priority = file upload → new image name → keep old image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/recipes/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $image_path = $_FILES['image']['name']; // Save only filename
    } elseif (!empty($new_image_name)) {
        $image_path = $new_image_name;
    } else {
        $image_path = $recipe->image_path; // Keep old image
    }

    //  Validate required fields
    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description are required.";
    }

    //  Update recipe if no errors
    if (empty($errors)) {
        $db->query("UPDATE recipes SET 
            title = :title,
            description = :description,
            ingredients = :ingredients,
            instructions = :instructions,
            prep_time = :prep_time,
            cook_time = :cook_time,
            servings = :servings,
            difficulty = :difficulty,
            category = :category,
            image_path = :image_path,
            updated_at = NOW()
            WHERE id = :id");

        // 🔹 Bind parameters
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
        $db->bind(":id", $recipe_id);

        $db->execute();
        $success = "✅ Recipe updated successfully!";
    }
}

ob_start();
?>

<h1>Edit Recipe</h1>
<a href="manage-recipes.php" class="btn btn-secondary">⬅ Back to Recipes</a>

<div class="form-container">

    <!--  Show error messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
        </div>
    <?php endif; ?>

    <!--  Show success message -->
    <?php if (!empty($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>

    <!-- Recipe Edit Form -->
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($recipe->title) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($recipe->description) ?></textarea>

        <label>Ingredients:</label>
        <textarea name="ingredients" required><?= htmlspecialchars($recipe->ingredients) ?></textarea>

        <label>Instructions:</label>
        <textarea name="instructions" required><?= htmlspecialchars($recipe->instructions) ?></textarea>

        <label>Prep Time (mins):</label>
        <input type="number" name="prep_time" value="<?= $recipe->prep_time ?>">

        <label>Cook Time (mins):</label>
        <input type="number" name="cook_time" value="<?= $recipe->cook_time ?>">

        <label>Servings:</label>
        <input type="number" name="servings" value="<?= $recipe->servings ?>">

        <label>Difficulty:</label>
        <select name="difficulty">
            <option value="Easy" <?= $recipe->difficulty == "Easy" ? "selected" : "" ?>>Easy</option>
            <option value="Medium" <?= $recipe->difficulty == "Medium" ? "selected" : "" ?>>Medium</option>
            <option value="Hard" <?= $recipe->difficulty == "Hard" ? "selected" : "" ?>>Hard</option>
        </select>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($recipe->category) ?>">

        <!--  New Image Handling (Same as Edit Product) -->
        <label>Current Image Path:</label>
        <input type="text" value="<?= htmlspecialchars($recipe->image_path) ?>" readonly>

        <label>New Image Path (Optional):</label>
        <input type="text" name="new_image_name" placeholder="Enter new image file name (e.g., tiramisu.jpg)">

        <p><em>Note:</em> The image must be placed inside <strong>assets/images/recipes/</strong> for example typy (tiramisu.jpg).</p>

        <label>Or Upload a New Image:</label>
        <input type="file" name="image">

        <?php if ($recipe->image_path): ?>
            <p>Current Image:</p>
            <img src="../assets/images/recipes/<?= htmlspecialchars($recipe->image_path) ?>" width="100">
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<style>
.form-container {
  max-width: 800px;
  margin: 30px auto;
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.admin-form label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}
.admin-form input,
.admin-form textarea,
.admin-form select {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
  border: 1px solid #ccc;
  border-radius: 6px;
}
.admin-form button {
  margin-top: 15px;
}
</style>

<?php
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
