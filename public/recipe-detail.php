<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// If no recipe ID is provided in the URL, redirect to recipes page
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ' . SITE_URL . '/public/recipes.php');
    exit;
}

// Get recipe details using the provided ID
$recipe_id = intval($_GET['id']);
$recipe = getRecipeById($recipe_id);

// If the recipe doesn't exist, redirect to recipes page
if(!$recipe) {
    header('Location: ' . SITE_URL . '/public/recipes.php');
    exit;
}
// Fetch reviews for this recipe
$reviews = getReviews('recipe', $recipe_id);

// Calculate average rating
$average_rating = 0;
if(count($reviews) > 0) {
    $total_rating = 0;
    foreach($reviews as $review) {
        $total_rating += $review->rating;
    }
    $average_rating = $total_rating / count($reviews);
}

// Set page title and description for SEO
$page_title = $recipe->title;
$page_description = substr(strip_tags($recipe->description), 0, 150) . '...';

// Start output buffering to store page content
ob_start();
?>
<div class="recipe-detail-container">
    <div class="recipe-header">
    <!-- Recipe title and metadata -->
        <h1><?php echo htmlspecialchars($recipe->title); ?></h1>
        <div class="recipe-meta">
            <span>Posted by <?php echo htmlspecialchars($recipe->username ?? 'Admin'); ?></span>
            <span><?php echo date('M d, Y', strtotime($recipe->created_at)); ?></span>
            <span><?php echo $recipe->prep_time + $recipe->cook_time; ?> mins</span>
            <span><?php echo $recipe->servings; ?> servings</span>
            <span><?php echo ucfirst($recipe->difficulty); ?></span>
        </div>

        <!-- Display average rating if available -->
        <?php if($average_rating > 0): ?>
            <div class="recipe-rating">
                <div class="stars" style="--rating: <?php echo $average_rating; ?>;"></div>
                <span>(<?php echo count($reviews); ?> reviews)</span>
            </div>
        <?php endif; ?>
        <!-- Buttons to save or share the recipe -->
        <div class="recipe-actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <button class="btn btn-save-recipe" data-recipe-id="<?php echo $recipe->id; ?>">
                    <?php echo isRecipeSaved($_SESSION['user_id'], $recipe->id) ? 'Saved' : 'Save Recipe'; ?>
                </button>
            <?php endif; ?>
            <button class="btn btn-share">Share</button>
        </div>
    </div>
    
    <div class="recipe-content">
        <!-- Display recipe image -->
        <div class="recipe-image">
            <img src="<?php echo SITE_URL; ?>/assets/images/recipes/<?php echo $recipe->image_path; ?>" alt="<?php echo htmlspecialchars($recipe->title); ?>">
        </div>

        <!-- Display recipe description -->
        <div class="recipe-description">
            <p><?php echo nl2br(htmlspecialchars($recipe->description)); ?></p>
        </div>

        <!-- Show ingredients and instructions side by side -->
        <div class="recipe-details-grid">
            <div class="ingredients">
                <h3>Ingredients</h3>
                <ul>
                    <?php 
                    $ingredients = explode("\n", $recipe->ingredients);
                    foreach($ingredients as $ingredient): 
                        if(trim($ingredient) != ''):
                    ?>
                        <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>
            
            <div class="instructions">
                <h3>Instructions</h3>
                <ol>
                    <?php 
                    $instructions = explode("\n", $recipe->instructions);
                    foreach($instructions as $instruction): 
                        if(trim($instruction) != ''):
                    ?>
                        <li><?php echo htmlspecialchars(trim($instruction)); ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ol>
            </div>
        </div>
        
        <!-- Display video tutorial if available -->
        <?php if($recipe->video_path): ?>
            <div class="recipe-video">
                <h3>Video Tutorial</h3>
                <video controls>
                    <source src="<?php echo SITE_URL; ?>/assets/videos/<?php echo $recipe->video_path; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="recipe-reviews">
        <h3>Reviews</h3>
         <!-- If user is logged in, allow them to add a review -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="add-review">
                <h4>Add Your Review</h4>
            <form id="review-form" class="review-form" action="<?php echo SITE_URL; ?>/public/submit_review.php" method="POST">
                <input type="hidden" name="item_type" value="recipe">
                <input type="hidden" name="item_id" value="<?php echo $recipe->id; ?>">

                <div class="rating-input">
                    <span>Rating:</span>
                    <div class="stars-input">
                        <?php for($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star-<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                            <label for="star-<?php echo $i; ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                <textarea name="comment" placeholder="Share your thoughts about this recipe..." required></textarea>
                <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>


            </div>
        <?php else: ?>
            <!-- Ask user to log in if they are not logged in -->
            <p><a href="<?php echo SITE_URL; ?>/public/login.php">Login</a> to leave a review.</p>
        <?php endif; ?>
        <!-- Display all reviews if any exist -->
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
</div>

<script>
// JavaScript to handle saving and sharing recipes
document.addEventListener("DOMContentLoaded", () => {
    const saveBtn = document.querySelector(".btn-save-recipe");
    if (saveBtn) {
        saveBtn.addEventListener("click", () => {
            const recipeId = saveBtn.dataset.recipeId;

            fetch("../private/toggle_favorite.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "recipe_id=" + recipeId
            })
            .then(res => res.text())
            .then(response => {
                if (response === "added") {
                    saveBtn.textContent = "Saved";
                } else if (response === "removed") {
                    saveBtn.textContent = "Save Recipe";
                } else if (response === "not_logged_in") {
                    alert("Please log in to save recipes!");
                }
            });
        });
    }

    // Handle Share button click
    const shareBtn = document.querySelector(".btn-share");
    if (shareBtn) {
    shareBtn.addEventListener("click", () => {
        const recipeId = <?= $recipe->id ?>;
        window.location.href = "../private/share.php?id=" + recipeId;
    });
}


});
</script>




<?php
// Capture all content and include header and footer
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>