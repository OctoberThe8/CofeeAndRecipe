<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Redirect if user is not logged in
// If user is not logged in, show a custom message page
if (!isset($_SESSION['user_id'])) {
    ob_start(); ?>

    <section class="contact-page">
        <h1>Access Denied 🚫</h1>
        <p>You must be part of our family to contact us.  
        Please log in or register to send us a message.</p>

        <div style="margin-top: 20px;">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </section>

    <?php
    $content = ob_get_clean();
    include '../includes/header.php';
    echo $content;
    include '../includes/footer.php';
    exit;
}


// Fetch logged-in user's info
$db = new Database();
$db->query("SELECT email, username FROM users WHERE id = :id");
$db->bind(":id", $_SESSION['user_id']);
$user = $db->single();
// If user not found, display a message
if (!$user) {
    echo "<p>User not found. Please log in again.</p>";
    exit;
}
// Store user email and username for later use
$user_email = $user->email;
$user_name = $user->username;

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (empty($message)) {
        $errors[] = "Message is required.";
    }
    // If no errors, attempt to send an email
    if (empty($errors)) {
        $to = "senah@uwindsor.ca"; // Fixed company email my email
        $subject = "New Contact Message from $user_name";
        $body = "You received a new message from $user_name ($user_email):\n\n$message";

        $headers = "From: no-reply@coffeerecipes.com\r\n";
        $headers .= "Reply-To: $user_email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            $success = "✅ Your message has been sent!";
        } else {
            $errors[] = "❌ Email could not be sent. Try again later.";
        }
    }
}
// Start capturing the page content
ob_start();
?>

<section class="contact-page">
    <h1>Get in Touch</h1>

    <div class="contact-container">
        <!--  Left Side: Contact Info -->
        <div class="contact-info">
            <h2><i class="fas fa-map-marker-alt"></i> Visit Us</h2>
            <p>123 Coffee Ave, Windsor City, ON N1A 2H3</p>

            <h2><i class="fas fa-phone"></i> Call Us</h2>
            <p>(123) 456-7890</p>

            <h2><i class="fas fa-envelope"></i> Email Us</h2>
            <p>info@coffeerecipes.com</p>

            
        </div>

        <!--  Right Side: Contact Form -->
        <div class="contact-form">
            <h2>Send a Message</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert success">
                    <p><?= $success ?></p>
                </div>
            <?php endif; ?>
            <!-- Contact form -->
            <form method="POST">
                <div class="form-group">
                    <label>Your Email</label>
                    <input type="email" value="<?= htmlspecialchars($user_email) ?>" disabled>
                    <input type="hidden" name="email" value="<?= htmlspecialchars($user_email) ?>">
                </div>

                <div class="form-group">
                    <label>Your Message</label>
                    <textarea name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>

    <!--  Map Section (OpenStreetMap + Leaflet.js) -->
    <div id="map" style="height: 450px; width: 100%; margin-top:20px;"></div>

    <!--  Leaflet.js CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // my company coordinates
        var companyLocation = [42.3073, -83.0660]; // University of Windsor

        // Create map
        var map = L.map("map").setView(companyLocation, 15);

        // Add free map tiles
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker with popup
        L.marker(companyLocation)
            .addTo(map)
            .bindPopup("<b>Our Coffee Shop</b><br>123 Coffee Ave, Windsor City, ON N1A 2H3")
            .openPopup();
    });
    </script>
</section>

<?php
$content = ob_get_clean();
include '../includes/header.php';
echo $content;
include '../includes/footer.php';
?>
