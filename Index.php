<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

// get user's favorites
$favs = [];
if (isset($_SESSION['user_id'])) {
    $favQuery = $pdo->prepare("
        SELECT r.*
        FROM recipes r
        JOIN favorites f ON f.recipe_id = r.id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
    ");
    $favQuery->execute([$_SESSION['user_id']]);
    $favs = $favQuery->fetchAll();
}

// get all recipes
$allQuery = $pdo->query("SELECT * FROM recipes ORDER BY created_at DESC")->fetchAll();

// remove favorites from full list
$favoriteIds = array_column($favs, 'id');
$recipes = array_filter($allQuery, function ($r) use ($favoriteIds) {
    return !in_array($r['id'], $favoriteIds);
});
?>

<h2>🍽 Welcome to Recipe Hub</h2>

<!-- ❤️ FAVORITES SECTION -->
<?php if (!empty($favs)): ?>
    <div style="background-color: #fff4f4; padding: 15px; border: 1px solid #ffaaaa; margin-bottom: 30px;">
        <h3 style="color: red;">❤️ Your Favorite Recipes</h3>
        <?php foreach ($favs as $r): ?>
            <div style="margin-bottom: 15px;">
                <strong><?php echo htmlspecialchars($r['title']); ?></strong><br>
                <?php echo nl2br(htmlspecialchars(substr($r['description'], 0, 50))) . '...'; ?><br>
                <a href="recipe_detail.php?id=<?php echo $r['id']; ?>">View Recipe</a>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- 🔥 OTHER RECIPES SECTION -->
<h3>🆕 All Recipes</h3>
<?php foreach ($recipes as $r): ?>
    <div style="margin-bottom: 15px;">
        <strong><?php echo htmlspecialchars($r['title']); ?></strong><br>
        <?php echo nl2br(htmlspecialchars(substr($r['description'], 0, 50))) . '...'; ?><br>
        <a href="recipe_detail.php?id=<?php echo $r['id']; ?>">View Recipe</a>
    </div>
    <hr>
<?php endforeach; ?>
