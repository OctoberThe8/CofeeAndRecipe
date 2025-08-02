<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = 'Home';
$page_description = 'Discover delicious coffee recipes and premium coffee products';
$featured_recipes = getFeaturedRecipes();
$featured_products = getProductsByCategory('featured', 4);

ob_start();
?>
<!-- Hero Section with Welcome Message -->
<section class="hero">
    <div class="hero-content">
        <!-- Friendly Introduction -->
        <div class="welcome-message">
            <h1>Welcome to Coffee & Recipes!</h1>
            <!--adding the intro paragraph to my website -->
            <p class="intro-text">
                Hello and welcome to our cozy corner of the internet, where coffee lovers and baking enthusiasts 
                come together to share delicious recipes, discover premium coffee products, and connect over a 
                shared love for all things warm and wonderful. Whether you're here to explore new coffee recipes, 
                shop for high-quality beans and tools, or simply find inspiration for your next kitchen adventure, 
                we're thrilled to have you. Our site is designed to be your go-to resource for everything from perfect 
                espresso techniques to decadent coffee-infused desserts, and we've made sure it's as easy to use as it is 
                delightful to browse. 
            </p>
            <p>☕🍪 Happy exploring!</p>
        </div>

        <h2>Discover the Art of Coffee & Baking</h2>
        <p>Explore our collection of premium recipes and products to elevate your coffee experience.</p>
        <div class="hero-buttons">
            <a href="<?php echo SITE_URL; ?>/public/recipes.php" class="btn btn-primary">Browse Recipes</a>
            <a href="<?php echo SITE_URL; ?>/public/products.php" class="btn btn-secondary">Shop Products</a>
        </div>
    </div>
</section>

<!-- Featured Video Section -->
<section class="featured-video">
    <h2>Featured Coffee Tutorials</h2>
    <p class="video-description">Check out these amazing coffee tutorials to enhance your <b>brewing skills</b> and <b>History</b>.</p>

    <!-- Flex container for side-by-side videos -->
    <div class="video-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
        <!-- First Video -->
        <iframe width="560" height="315" 
                src="https://www.youtube.com/embed/vFcS080VYQ0" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>

        <!-- Second Video -->
        <iframe width="560" height="315" 
                src="https://www.youtube.com/embed/cxYhjF3APds" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    </div>

</section>






<!-- Featured Recipes Section -->
<section class="featured-section">
    <h2>Featured Recipes</h2>
    <div class="recipe-grid">
        <?php foreach($featured_recipes as $recipe): ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>