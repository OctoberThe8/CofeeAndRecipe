<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Create Auth instance and ensure only logged-in admins can access
$auth = new Auth();
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header("Location: ../public/login.php");
    exit;
}

$db = new Database();
$page_title = "Manage Recipes";

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $db->query("DELETE FROM recipes WHERE id = :id");
    $db->bind(":id", $delete_id);
    $db->execute();
    header("Location: manage-recipes.php");
    exit;
}

// ✅ Fetch All Recipes
$db->query("SELECT * FROM recipes ORDER BY created_at DESC");
$recipes = $db->resultSet();

ob_start();
?>
<h1>Manage Recipes</h1>
<!-- Buttons for adding a new recipe or going back -->
<div class="action-buttons">
  <a href="add-recipe.php" class="btn btn-small">➕ Add New Recipe</a>
  <a href="dashboard.php" class="btn btn-small">⬅ Back to Dashboard</a>
</div>

<!--  Recipes Table -->
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Category</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($recipes as $recipe): ?>
      <tr>
      <!--  Display each recipe's details -->
        <td><?= $recipe->id ?></td>
        <td><?= htmlspecialchars($recipe->title) ?></td>
        <td><?= htmlspecialchars($recipe->category) ?></td>
        <td><?= date("M d, Y", strtotime($recipe->created_at)) ?></td>
        <td>
        <!--  Edit and Delete Buttons -->
          <a href="edit-recipe.php?id=<?= $recipe->id ?>" class="btn btn-sm btn-edit">Edit</a>
          <a href="?delete=<?= $recipe->id ?>" class="btn btn-sm btn-delete" onclick="return confirm('Delete this recipe?');">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
// Render final page inside admin layout
$content = ob_get_clean();
include '../admin/admin-header.php';
echo $content;
include '../admin/admin-footer.php';
?>
