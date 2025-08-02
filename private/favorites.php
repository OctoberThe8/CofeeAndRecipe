<?php
// Include configuration and authentication
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Create an Auth instance and check if the user is logged in
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$page_title = 'My Favorites';
$user_id = $_SESSION['user_id'];

// Fetch the logged-in user's saved favorite recipes
$db = new Database();
$db->query('SELECT f.id AS fav_id, r.* 
            FROM favorites f 
            JOIN recipes r ON f.recipe_id = r.id 
            WHERE f.user_id = :user_id');
$db->bind(':user_id', $user_id);
$favorite_recipes = $db->resultSet();

// Start output buffering
ob_start();
?>
<section class="favorites-page">
    <h1>My Saved Favorites</h1>
    
    <!-- If user has saved favorites, display them -->
    <?php if (!empty($favorite_recipes)): ?>
        <div class="favorites-grid">
            <?php foreach ($favorite_recipes as $recipe): ?>
                <div class="favorite-card">
                <!-- Link to the recipe details page -->
                    <a href="../public/recipe-detail.php?id=<?= $recipe->id; ?>">
                        <img src="<?= SITE_URL; ?>/assets/images/recipes/<?= $recipe->image_path; ?>" alt="<?= htmlspecialchars($recipe->title); ?>">
                        <h3><?= htmlspecialchars($recipe->title); ?></h3>
                    </a>
                    <!-- Button to remove the recipe from favorites -->
                    <button class="btn btn-remove-favorite" data-favorite-id="<?= $recipe->fav_id; ?>">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
    <!-- If no favorites, show an empty state message -->
        <div class="empty-state">
            <i class="fas fa-heart"></i>
            <p>You haven't saved any favorites yet.</p>
            <a href="../public/recipes.php" class="btn btn-primary">Browse Recipes</a>
        </div>
    <?php endif; ?>
</section>

<!-- JavaScript to handle removing a recipe from favorites -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".btn-remove-favorite").forEach(btn => {
        btn.addEventListener("click", () => {
            if (!confirm("Remove this recipe from favorites?")) return;

            const favId = btn.dataset.favoriteId;
            
            // Send a request to remove the recipe from favorites
            fetch("../private/remove_favorite.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "favorite_id=" + favId
            })
            .then(res => res.text())
            .then(response => {
                if (response === "removed") {
                    // Remove the recipe card from the page
                    btn.closest(".favorite-card").remove();
                } else {
                    alert("Error removing favorite!");
                }
            });
        });
    });
});
</script>

<?php
// Store the buffered content and include header/footer
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>
