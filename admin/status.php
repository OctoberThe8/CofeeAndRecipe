<?php
// Start session for authentication check if needed
session_start();

// Include configuration and database connection files
require_once '../includes/config.php';
require_once '../includes/db.php';

// Create a new database instance
$db = new Database();

ob_start();

// Function to check DB
function checkDatabase($db) {
    try {
        $db->query("SELECT 1");
        $db->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Function to check a page URL
function checkPage($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

// Status checks
$db_status = checkDatabase($db);
$login_status = checkPage(SITE_URL . "/public/login.php");
$products_status = checkPage(SITE_URL . "/public/products.php");
$recipes_status = checkPage(SITE_URL . "/public/recipes.php");
$contact_status = checkPage(SITE_URL . "/public/contact.php");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Website Status</title>
    <style>
            /*  Basic Styling for the Dashboard */
        body { font-family: Arial, sans-serif; text-align: center; padding: 30px; }
        table { width: 60%; margin: auto; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 12px; }
        .online { color: green; font-weight: bold; }
        .offline { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Website Monitoring Dashboard</h1>
    
    <!--  Table to display service statuses -->
    <table>
        <tr><th>Service</th><th>Status</th></tr>
        <tr><td>Database</td><td class="<?= $db_status ? 'online' : 'offline' ?>"><?= $db_status ? 'ONLINE' : 'OFFLINE' ?></td></tr>
        <tr><td>Login Page</td><td class="<?= $login_status ? 'online' : 'offline' ?>"><?= $login_status ? 'ONLINE' : 'OFFLINE' ?></td></tr>
        <tr><td>Products Page</td><td class="<?= $products_status ? 'online' : 'offline' ?>"><?= $products_status ? 'ONLINE' : 'OFFLINE' ?></td></tr>
        <tr><td>Recipes Page</td><td class="<?= $recipes_status ? 'online' : 'offline' ?>"><?= $recipes_status ? 'ONLINE' : 'OFFLINE' ?></td></tr>
        <tr><td>Contact Form</td><td class="<?= $contact_status ? 'online' : 'offline' ?>"><?= $contact_status ? 'ONLINE' : 'OFFLINE' ?></td></tr>
    </table>
</body>
</html>
<?php
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>