<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set page title and description for SEO and header
$page_title = 'Recipes';
$page_description = 'Browse our collection of delicious coffee and baking recipes';
$category = $_GET['category'] ?? 'all';

// Get recipes based on category
$db = new Database();
// Fetch recipes based on the selected category
if ($category === 'all') {
    $db->query('SELECT * FROM recipes ORDER BY created_at DESC');
} else {
    $db->query('SELECT * FROM recipes WHERE category = :category ORDER BY created_at DESC');
    $db->bind(':category', $category);
}
// Get all recipe records
$recipes = $db->resultSet();

// Start output buffering to capture page content
ob_start();
?>
<section class="recipe-page">
    <div class="category-filter">
        <h2>My Coffee Recipes</h2>
        
    </div>
    <!-- Display all recipes in a grid layout -->
    <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
    <a href="recipe-detail.php?id=<?php echo $recipe->id; ?>">
        <div class="recipe-info-top">
            <h1 class="recipe-title"><?php echo htmlspecialchars($recipe->title); ?></h1>
        </div>
        <!-- Display recipe image -->
        <img src="<?php echo SITE_URL; ?>/assets/images/recipes/<?php echo $recipe->image_path; ?>" alt="<?php echo htmlspecialchars($recipe->title); ?>">
        <div class="recipe-meta-bottom">
            <span><i class="fas fa-clock"></i> <?php echo $recipe->prep_time + $recipe->cook_time; ?> mins</span>
            <span><i class="fas fa-utensils"></i> <?php echo $recipe->servings; ?> servings</span>
        </div>
    </a>
</div>

        <?php endforeach; ?>
    </div>
</section>

<?php
// Save the buffered content and iclude the footer and the header
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>