<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Set page title and description for SEO and header
$page_title = 'Products';
$page_description = 'Discover our premium coffee products and brewing equipment';
$category = $_GET['category'] ?? 'all';

// Create a database instance to fetch products
$db = new Database();

// If "all" is selected, get all products in stock
if ($category === 'all') {
    $db->query('SELECT * FROM products WHERE stock > 0 ORDER BY created_at DESC');
} else {

// Otherwise, get products filtered by category
    $db->query('SELECT * FROM products WHERE category = :category AND stock > 0 ORDER BY created_at DESC');
    $db->bind(':category', $category);
}
// Fetch all products from the database
$products = $db->resultSet();

ob_start();
?>
<section class="products-page">
    <div class="category-filter">
        <h2>Our Products</h2>
        <!-- Buttons to filter products by category -->
        <div class="filter-buttons">
            <a href="?category=all" class="btn <?php echo $category === 'all' ? 'active' : ''; ?>">All Products</a>
            <a href="?category=beans" class="btn <?php echo $category === 'beans' ? 'active' : ''; ?>">Coffee Beans</a>
            <a href="?category=equipment" class="btn <?php echo $category === 'equipment' ? 'active' : ''; ?>">Brewing Equipment</a>
            <a href="?category=accessory" class="btn <?php echo $category === 'accessory' ? 'active' : ''; ?>">Accessories</a>
        </div>
    </div>
    
    <!-- Display the products in a grid layout -->
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <a href="product-detail.php?id=<?php echo $product->id; ?>">
                    <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product->image_path; ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product->name); ?></h3>
                        <div class="product-price">$<?php echo number_format($product->price, 2); ?></div>
                        <?php if ($product->stock < 10): ?>
                            <div class="stock-warning">Only <?php echo $product->stock; ?> left!</div>
                        <?php endif; ?>
                    </div>
                </a>
                <!-- Add to Cart form -->
                <form method="post" action="cart.php" style="display:inline;">
                    <input type="hidden" name="add_to_cart" value="<?php echo $product->id; ?>">
                    <button type="submit" class="btn btn-add-to-cart">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>