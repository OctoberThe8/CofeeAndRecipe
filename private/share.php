<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Validate that a recipe ID is provided
if (!isset($_GET['id'])) {
    echo "<p>Invalid recipe</p>";
    exit;
}

$recipe_id = (int)$_GET['id'];

// Fetch recipe details using the helper function
$recipe = getRecipeById($recipe_id);

if (!$recipe) {
    echo "<p>Recipe not found</p>";
    exit;
}
// Start output buffering to capture the HTML content
ob_start();
?>

<div class="container">
    <section class="share-confirmation">
    <!-- Display the recipe title -->
        <h1>Share "<?= htmlspecialchars($recipe->title) ?>"</h1>
    <!-- Form to send the recipe to someone -->
        <form method="POST" action="send_share.php" class="share-form">
            <input type="hidden" name="recipe_id" value="<?= $recipe->id ?>">
            <input type="hidden" name="recipe_link" value="<?= SITE_URL ?>/public/recipe-detail.php?id=<?= $recipe->id ?>">

            <div class="form-group">
                <label>Your Name:</label>
                <input type="text" name="sender_name" required>
            </div>

            <div class="form-group">
                <label>Recipient Email:</label>
                <input type="email" name="recipient_email" required>
            </div>

            <div class="form-group">
                <label>Message:</label>
                <textarea name="message" placeholder="Hey, check out this recipe!" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Send Recipe</button>
        </form>
        
        <!-- Link to go back to the recipe page -->
        <p><a href="../public/recipe-detail.php?id=<?= $recipe->id ?>">← Back to Recipe</a></p>
    </section>
</div>

<?php
$content = ob_get_clean(); 
// Include the header, display the content, and include the footer
include '../includes/header.php'; 
echo $content;
include '../includes/footer.php';
?>
