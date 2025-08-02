<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic Page Title -->
    <title><?php echo isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Basic SEO -->
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Discover delicious coffee recipes and premium coffee products'; ?>">
    <meta name="keywords" content="coffee, recipe, organic beans, home baking, coffee brewing, espresso, latte, cappuccino">
    <meta name="author" content="Coffee & Recipes Team">
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">

    <!-- Open Graph (Facebook, LinkedIn) -->
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Discover delicious coffee recipes and premium coffee products'; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/R.png">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($page_title) ? $page_title : SITE_NAME; ?>">
    <meta name="twitter:description" content="<?php echo isset($page_description) ? $page_description : 'Discover delicious coffee recipes and premium coffee products'; ?>">
    <meta name="twitter:image" content="<?php echo SITE_URL; ?>/assets/images/R.png">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">

    <!-- Main site styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/theme-regular.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/theme-<?php echo getCurrentTheme(); ?>.css">

    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


<body class="<?php echo getCurrentTheme(); ?>">
    <header>
        <div class="container">
        <!-- Website logo linking to homepage -->
            <div class="logo">
                <a href="<?php echo SITE_URL; ?>/public/index.php">
                    <img src="<?php echo SITE_URL; ?>/assets/images/R.png" alt="coffee & recipes Logo" >
                </a>
            </div>
            <!-- Main navigation bar -->
            <nav class="main-nav">
                <button class="mobile-menu-toggle" aria-label="Toggle menu"> </button>
                <ul class="centered-nav">
                    <li>
                        <a href="<?php echo SITE_URL; ?>/public/index.php">
                        <i class="fas fa-home nav-icon"></i>
                        <span>Home</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo SITE_URL; ?>/public/recipes.php">
                            <i class="fas fa-book nav-icon"></i>
                            <span>Recipes</span>
                        </a>
                    </li>

                    
                    <li>
                        <a href="<?php echo SITE_URL; ?>/public/products.php">
                            <i class="fas fa-shopping-bag nav-icon"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/public/about.php">
                            <i class="fas fa-info-circle nav-icon"></i>
                            <span>About</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/public/contact.php">
                            <i class="fas fa-envelope nav-icon"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- User action buttons: cart, login/register or user menu -->
            <div class="user-actions">
                <a href="<?php echo SITE_URL; ?>/public/cart.php" class="cart-icon">
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <!-- Show user dropdown menu if logged in -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="user-btn">
                            <i class="fas fa-user-circle user-icon"></i>
                            <?php echo $_SESSION['username']; ?> ▼
                        </button>
                        <ul class="user-menu">
                            <li><a href="<?php echo SITE_URL; ?>/private/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/private/favorites.php"><i class="fas fa-heart"></i> Favorites</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/private/order-history.php"><i class="fas fa-history"></i> Orders</a></li>
                            <!-- Admin dashboard link for admins only -->
                            <?php if((new Auth())->isAdmin()): ?>
                                <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php"><i class="fas fa-cog"></i> Admin</a></li>
                            <?php endif; ?>
                            <!-- Logout button -->
                            <li><a href="<?php echo SITE_URL; ?>/public/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                    <!-- If not logged in, show login/register buttons -->
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/public/login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="<?php echo SITE_URL; ?>/public/register.php" class="register-btn"><i class="fas fa-user-plus"></i> Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container">