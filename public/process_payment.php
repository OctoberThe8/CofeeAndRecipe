<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$db = new Database();

// Collect user input
$user_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$shipping_address = $_POST['shipping_address'] ?? '';
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

// Insert order
$db->query("INSERT INTO orders (user_id, name, email, shipping_address, total, status, created_at)
            VALUES (:user_id, :name, :email, :shipping_address, :total, 'Pending', NOW())");
$db->bind(':user_id', $user_id);
$db->bind(':name', $name);
$db->bind(':email', $email);
$db->bind(':shipping_address', $shipping_address);
$db->bind(':total', $total);
$db->execute();

$order_id = $db->lastInsertId();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];

//Clean product IDs (in case keys have options like "4-option")
$clean_ids = [];
foreach (array_keys($cart) as $key) {
    $clean_ids[] = (int) $key; // remove any extra text, force integer
}

$placeholders = implode(',', array_fill(0, count($clean_ids), '?'));

$db->query("SELECT id, price, stock FROM products WHERE id IN ($placeholders)");
$i = 1;
foreach ($clean_ids as $id) {
    $db->bind($i++, $id);
}
$products = $db->resultSet();

foreach ($products as $p) {
    //Find the cart item that matches this product ID
    $quantity = 0;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['product_id'] == $p->id) {
            $quantity = $item['quantity'];
            break;
        }
    }

    //Save order item
    $db->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)");
    $db->bind(':order_id', $order_id);
    $db->bind(':product_id', $p->id);
    $db->bind(':quantity', $quantity);
    $db->bind(':price', $p->price);
    $db->execute();

    //Update stock
    if ($quantity > 0) {
        $db->query("UPDATE products SET stock = GREATEST(stock - :qty, 0) WHERE id = :id");
        $db->bind(':qty', $quantity);
        $db->bind(':id', $p->id);
        $db->execute();
    }
}


//Clear cart
unset($_SESSION['cart']);

//Redirect
header("Location: thank_you.html");
exit;
?>
