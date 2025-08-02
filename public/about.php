<?php
// Include configuration file for database connection and constants
require_once '../includes/config.php';

// Include helper functions for reusable code
require_once '../includes/functions.php';

// Set page metadata variables for title and description
$page_title = 'About Us';
$page_description = 'Learn about our passion for coffee and baking';

// Start output buffering to capture page content
ob_start();
?>

<section class="about-page">
    <div class="about-hero">
        <!-- Hero section with main heading and introduction paragraph -->
        <h1>Our Coffee Journey</h1>
        <p>Welcome to Coffee & Recipes, your go-to destination for all things coffee and delicious recipes! Our project is dedicated to sharing the art of brewing the perfect cup of coffee, along with mouthwatering recipes that pair perfectly with your favorite drink. Whether you're a coffee enthusiast looking for brewing tips, a home barista exploring latte art, or a food lover searching for sweet and savory treats, we’ve got you covered. From classic espresso techniques to creative coffee-infused desserts, our goal is to inspire your culinary journey with easy-to-follow guides, expert advice, and flavorful ideas. Grab your mug, explore our collection, and let’s brew something amazing together!</p>
    </div>

    <div class="about-content">
        <!-- Mission statement section -->
        <div class="mission">
            <h2> Our Mission</h2>
            <p>To bring coffee lovers and baking enthusiasts together through quality recipes, premium products, and shared knowledge. We believe every cup should be an experience and every bite should tell a story.</p>
        </div>

        <!-- Team section introducing team members -->
        <div class="team">
            <h2> Meet the Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <!-- Display head barista info -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/owner.jpg" alt="Head Barista">
                    <h3>Hanan Windsor</h3>
                    <p>Head Barista & Recipe Developer</p>
                </div>
                <div class="team-member">
                    <!-- Display master baker info -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/chef.jpg" alt="Master Baker">
                    <h3>Jack Oscar</h3>
                    <p>Master Baker</p>
                </div>
            </div>
        </div>

        <!-- Values section listing core principles -->
        <div class="values">
            <h2> Our Values</h2>
            <ul>
                <li><strong>Quality:</strong> Only the finest beans and ingredients</li>
                <li><strong>Community:</strong> Sharing knowledge and passion</li>
                <li><strong>Sustainability:</strong> Ethically sourced products</li>
                <li><strong>Innovation:</strong> Constantly exploring new flavors</li>
            </ul>
        </div>
    </div>

<!-- Add YouTube Video at the Bottom -->
    <div class="about-video" style="text-align:center; margin-top: 30px;">
        <h2>Watch Our LOVE ----> COFFEE</h2>
        <iframe width="560" height="315" 
                src="https://www.youtube.com/embed/0jIeCAOkgcQ" 
                title="YouTube video player" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    </div>


</section>

<?php
// Store all buffered content into $content variable
$content = ob_get_clean();

// Include the header file to display the page header
include '../includes/header.php';

// Output the captured page content
echo $content;

// Include the footer file to display the page footer
include '../includes/footer.php';
?>
