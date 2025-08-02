<?php
// Include configuration and helper functions
require_once '../includes/config.php';
require_once '../includes/functions.php';

// If the form was submitted, update the theme in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_theme = $_POST['theme'];

    // Update the current theme setting in the database
    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_name = 'current_theme'");
    $stmt->execute([$new_theme]);

    // Redirect back to the page with a success message
    header("Location: admin_theme.php?success=1");
    exit;
}

// Get the currently active theme from the database
$current_theme = getSetting('current_theme');
?>

<h2>Choose Site Theme</h2>

<!-- Display a success message if the theme was updated -->
<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Theme updated successfully!</p>
<?php endif; ?>

<!-- Theme selection form -->
<form method="post">
    <select name="theme">
        <option value="regular" <?= $current_theme == 'regular' ? 'selected' : '' ?>>Regular</option>
        <option value="Christmas" <?= $current_theme == 'Christmas' ? 'selected' : '' ?>>Christmas</option>
        <option value="halloween" <?= $current_theme == 'halloween' ? 'selected' : '' ?>>Halloween</option>
    </select>
    <button type="submit">Save Theme</button>
</form>
