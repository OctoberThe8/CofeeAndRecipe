<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_name = htmlspecialchars($_POST['sender_name']);
    $recipient_email = htmlspecialchars($_POST['recipient_email']);
    $message = htmlspecialchars($_POST['message']);
    $recipe_link = htmlspecialchars($_POST['recipe_link']);

    // Email Setup
    $subject = "$sender_name shared a recipe with you!";
    $body = "Hi,\n\n$sender_name wanted to share this recipe with you:\n\n$message\n\nView Recipe: $recipe_link\n";

    //Some servers require "From" to be your domain. Use Reply-To for user.
    $headers = "From: no-reply@Coffee.com\r\n";
    $headers .= "Reply-To: $sender_name <$recipient_email>\r\n";

    // Send Email
    if (!mail($recipient_email, $subject, $body, $headers)) {
        error_log("Mail failed to $recipient_email");
    }

    //Show Confirmation Page
    ob_start();
    ?>
    <div class="container">
        <section class="share-confirmation">
            <h1>Recipe Shared!</h1>
            <p><strong><?= $sender_name ?></strong> shared this recipe with <strong><?= $recipient_email ?></strong>.</p>
            <p><strong>Message:</strong> <?= nl2br($message) ?></p>
            <p><a href="<?= $recipe_link ?>" class="btn btn-primary">🔗 View Recipe</a></p>
        </section>
    </div>
    <?php
    $content = ob_get_clean();
    include '../includes/header.php';
    echo $content;
    include '../includes/footer.php';
}
