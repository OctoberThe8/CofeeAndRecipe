<?php
// Helper functions

function getCurrentTheme() {
    if (isset($_SESSION['theme'])) {
        return $_SESSION['theme'];
    }

    $db = new Database;
    $db->query('SELECT setting_value FROM site_settings WHERE setting_name = "current_theme"');
    $result = $db->single();

    return $result ? $result->setting_value : 'regular';
}

function setTheme($theme) {
    $validThemes = ['regular', 'halloween', 'Christmas', 'easter'];

    if (!in_array($theme, $validThemes)) return;

    $_SESSION['theme'] = $theme;

    $db = new Database;
    $db->query('UPDATE site_settings SET setting_value = :theme WHERE setting_name = "current_theme"');
    $db->bind(':theme', $theme);
    $db->execute();
}





// Get products by category
function getProductsByCategory($category, $limit = null) {
    $db = new Database;
    $sql = 'SELECT * FROM products WHERE category = :category';
    if($limit) {
        $sql .= ' LIMIT ' . $limit;
    }
    $db->query($sql);
    $db->bind(':category', $category);
    return $db->resultSet();
}

// Get featured recipes
function getFeaturedRecipes($limit = 3) {
    $db = new Database;
    $db->query('SELECT * FROM recipes ORDER BY created_at DESC LIMIT ' . $limit);
    return $db->resultSet();
}

// Get recipe by ID
function getRecipeById($id) {
    $db = new Database;
    $db->query('SELECT r.*, u.username FROM recipes r LEFT JOIN users u ON r.user_id = u.id WHERE r.id = :id');
    $db->bind(':id', $id);
    return $db->single();
}

// Get product by ID
function getProductById($id) {
    $db = new Database;
    $db->query('SELECT * FROM products WHERE id = :id');
    $db->bind(':id', $id);
    return $db->single();
}

function getReviews($type, $item_id) {
    $db = new Database();
    
    if ($type === 'recipe') {
        $db->query("SELECT r.*, u.username 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.item_type = ? AND r.recipe_id = ? 
                    ORDER BY r.created_at DESC");
    } else if ($type === 'product') {
        $db->query("SELECT r.*, u.username 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.item_type = ? AND r.product_id = ? 
                    ORDER BY r.created_at DESC");
    } else {
        // Optional: throw error or return empty if type is unrecognized
        return [];
    }

    $db->bind(1, $type);
    $db->bind(2, $item_id);
    return $db->resultSet();
}


// Add to cart function
function addToCart($product_id, $quantity = 1, $options = []) {
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $key = $product_id . serialize($options);
    
    if(isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'options' => $options
        ];
    }
}

// Get cart items with product details
function getCartItems() {
    if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    
    $cartItems = [];
    $db = new Database;
    
    foreach($_SESSION['cart'] as $item) {
        $db->query('SELECT id, name, price, image_path FROM products WHERE id = :id');
        $db->bind(':id', $item['product_id']);
        $product = $db->single();
        
        if($product) {
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'options' => $item['options']
            ];
        }
    }
    
    return $cartItems;
}

// Calculate cart total
function calculateCartTotal() {
    $cartItems = getCartItems();
    $total = 0;
    
    foreach($cartItems as $item) {
        $total += $item['product']->price * $item['quantity'];
    }
    
    return $total;
}

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function isProductSaved($user_id, $product_id) {
    $db = new Database();

    $db->query("SELECT COUNT(*) as count FROM favorites WHERE user_id = :uid AND product_id = :pid");
    $db->bind(':uid', $user_id);
    $db->bind(':pid', $product_id);
    $row = $db->single();

    return $row && $row->count > 0;
}

function isRecipeSaved($user_id, $recipe_id) {
    $db = new Database();

    $db->query("SELECT COUNT(*) as count FROM favorites WHERE user_id = :uid AND recipe_id = :pid");
    $db->bind(':uid', $user_id);
    $db->bind(':pid', $recipe_id);
    $row = $db->single();

    return $row && $row->count > 0;
}

?>