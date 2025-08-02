<?php
session_start();
ob_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
include '../includes/header.php';

// Redirect to products page if no product ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ' . SITE_URL . '/public/products.php');
    exit;
}

// Get product ID from the URL and fetch product details
$product_id = intval($_GET['id']);
$product = getProductById($product_id);

// If product is not found, redirect to products page
if(!$product) {
    header('Location: ' . SITE_URL . '/public/products.php');
    exit;
}

// Fetch reviews for the product
$reviews = getReviews('product', $product_id);
// Calculate average rating if there are reviews
$average_rating = 0;
if(count($reviews) > 0) {
    $total_rating = 0;
    foreach($reviews as $review) {
        $total_rating += $review->rating;
    }
    $average_rating = $total_rating / count($reviews);
}

// Decode options JSON or set to empty array if none
$options = [];
if (!empty($product->options)) {
    $decoded = json_decode($product->options, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        // If the decoded JSON is just a list, wrap it under "Options"
        if (array_keys($decoded) === range(0, count($decoded) - 1)) {
            $options = ['Options' => $decoded];
        } else {
            $options = $decoded;
        }
    }
}
// Set the page title and description for SEO and header usage
$page_title = $product->name;
$page_description = substr(strip_tags($product->description), 0, 150) . '...';
?>
<div class="product-detail-container">
    <div class="product-images">
        <img src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo $product->image_path; ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
    </div>
    
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product->name); ?></h1>
        
        <div class="product-price">
            $<?php echo number_format($product->price, 2); ?>
            <?php if($product->stock > 0): ?>
                <span class="in-stock">In Stock (<?php echo $product->stock; ?> available)</span>
            <?php else: ?>
                <span class="out-of-stock">Out of Stock</span>
            <?php endif; ?>
        </div>

        <!-- Product description -->
        <div class="product-description">
            <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
        </div>
        <!-- Add to cart form -->
        <form id="add-to-cart-form" class="product-options-form" method="post" action="cart.php">
            <input type="hidden" name="add_to_cart" value="<?php echo $product->id; ?>">
            
            <!-- Display product options if available -->
            <?php if(!empty($options)): ?>
                <?php foreach($options as $option_name => $option_values): ?>
                    <?php if(is_array($option_values)): ?>
                        <div class="form-group option-group">
                            <label><?php echo htmlspecialchars($option_name); ?></label>
                            <div class="option-buttons">
                                <?php foreach($option_values as $value): ?>
                                    <button type="button" 
                                            class="option-btn <?php echo ($value === reset($option_values)) ? 'selected' : ''; ?>" 
                                            data-option-name="<?php echo htmlspecialchars($option_name); ?>" 
                                            data-option-value="<?php echo htmlspecialchars($value); ?>">
                                        <?php echo htmlspecialchars($value); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <!-- Hidden input to store selected option value -->
                            <input type="hidden" 
                                   name="options[<?php echo htmlspecialchars($option_name); ?>]" 
                                   value="<?php echo htmlspecialchars(reset($option_values)); ?>">
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Quantity selector -->
            <div class="form-group quantity-group">
                <label for="quantity">Quantity</label>
                <div class="quantity-selector">
                    <button type="button" class="quantity-btn minus">-</button>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->stock; ?>">
                    <button type="button" class="quantity-btn plus">+</button>
                </div>
            </div>
            <!-- Add to cart button (disabled if product is out of stock) -->
            <button type="submit" class="btn btn-primary btn-add-to-cart" <?php echo $product->stock == 0 ? 'disabled' : ''; ?>>
                Add to Cart
            </button>
        </form>
        <!-- Additional product details -->
        <div class="product-details">
            <h3>Product Details</h3>
            <ul>
                <li><strong>Category:</strong> <?php echo htmlspecialchars($product->category); ?></li>
                <li><strong>Owner:</strong> Coffee & Recipe</li>
                <li><strong>Origin Shipping:</strong> Canada, Windsor</li>
            </ul>
        </div>
    </div>
</div>
<!-- Tabs for description, reviews, and shipping info -->
<div class="product-tabs">
    <ul class="tabs">
        <li class="active" data-tab="description">Description</li>
        <li data-tab="reviews">Reviews</li>
        <li data-tab="shipping">Shipping</li>
    </ul>

    <!-- Description tab -->
    <div class="tab-content active" id="description">
        <h3>About This Product</h3>
        <p><?php echo nl2br(htmlspecialchars($product->description)); ?></p>
    </div>

    <!-- Reviews tab -->
    <div class="tab-content" id="reviews">
        <h3>Customer Reviews</h3>

        <!-- If user is logged in, allow them to leave a review -->
       <?php if(isset($_SESSION['user_id'])): ?>
    <div class="add-review">
        <h4>Add Your Review</h4>
        <form id="product-review-form" class="review-form" action="<?php echo SITE_URL; ?>/public/submit_review.php" method="POST">
            <input type="hidden" name="item_type" value="product">
            <input type="hidden" name="item_id" value="<?php echo $product->id; ?>">
            
            <!-- Rating stars input -->
            <div class="rating-input">
                <span>Rating:</span>
                <div class="stars-input">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="product-star-<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                        <label for="product-star-<?php echo $i; ?>">★</label>
                    <?php endfor; ?>
                </div>
            </div>
            <!-- Review comment input -->
            <textarea name="comment" placeholder="Share your thoughts about this product..." required></textarea>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
<?php else: ?>
    <p><a href="<?php echo SITE_URL; ?>/public/login.php">Login</a> to leave a review.</p>
<?php endif; ?>

        <!-- Display list of reviews if available -->
        <div class="reviews-list">
            <?php if(count($reviews) > 0): ?>
                <?php foreach($reviews as $review): ?>
                    <div class="review">
                        <div class="review-header">
                            <span class="review-author"><?php echo htmlspecialchars($review->username); ?></span>
                            <div class="stars" style="--rating: <?php echo $review->rating; ?>;"></div>
                            <span class="review-date"><?php echo date('M d, Y', strtotime($review->created_at)); ?></span>
                        </div>
                        <div class="review-content">
                            <p><?php echo nl2br(htmlspecialchars($review->comment)); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet. Be the first to review!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Shipping tab -->
    <div class="tab-content" id="shipping">
        <h3>Shipping Information</h3>
        <p>We ship all orders within 1-2 business days. Shipping times vary depending on your location:</p>
        <ul>
            <li>Canada: 1-3 business days</li>
            <li>USA: 3-5 business days</li>
            <li>International: 7-14 business days</li>
        </ul>
        <br>
        <p>Enjoy free shipping worldwide, because great coffee should be accessible to everyone, no matter where you are! ☕🌎</p>    </div>
</div>

<style>
.product-detail-container {
    display: flex;
    gap: 2rem;
    margin: 2rem 0;
}

.product-images {
    flex: 1;
}

.product-images img {
    width: 100%;
    max-width: 500px;
    border-radius: 8px;
}

.product-info {
    flex: 1;
}

.option-group {
    margin-bottom: 1.5rem;
}

.option-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

.option-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.option-btn {
    padding: 0.5rem 1rem;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.option-btn:hover {
    background: #e9e9e9;
}

.option-btn.selected {
    background: #4a6fa5;
    color: white;
    border-color: #4a6fa5;
}

.quantity-group {
    margin: 2rem 0;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-btn {
    padding: 0.5rem 1rem;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

.quantity-btn:hover {
    background: #e9e9e9;
}

#quantity {
    width: 60px;
    text-align: center;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-add-to-cart {
    padding: 0.75rem 1.5rem;
    background: #4a6fa5;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.2s;
}

.btn-add-to-cart:hover {
    background: #3a5a80;
}

.btn-add-to-cart:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

.product-tabs {
    margin-top: 3rem;
}

.tabs {
    display: flex;
    list-style: none;
    padding: 0;
    border-bottom: 1px solid #ddd;
}

.tabs li {
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    border-bottom: 3px solid transparent;
}

.tabs li.active {
    border-bottom-color: #4a6fa5;
    font-weight: bold;
}

.tab-content {
    display: none;
    padding: 1.5rem 0;
}

.tab-content.active {
    display: block;
}
</style>

<!-- JavaScript to handle option selection, quantity changes, and tab switching -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Option buttons functionality
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const group = this.closest('.option-group');
            group.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            group.querySelector('input[type="hidden"]').value = this.dataset.optionValue;
        });
    });

    // Quantity selector
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('#quantity');
    
    minusBtn.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    });
    
    plusBtn.addEventListener('click', function() {
        let value = parseInt(quantityInput.value);
        if (value < <?php echo $product->stock; ?>) {
            quantityInput.value = value + 1;
        } else {
            alert('Maximum available quantity reached');
        }
    });

    // Tab switching
    document.querySelectorAll('.tabs li').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update active tab
            document.querySelectorAll('.tabs li').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update active content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>