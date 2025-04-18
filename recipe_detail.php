<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) die("No ID given.");

$stmt = $pdo->prepare("
    SELECT recipes.*, categories.name AS category_name
    FROM recipes
    LEFT JOIN categories ON recipes.category_id = categories.id
    WHERE recipes.id = ?
");
$stmt->execute([$id]);
$recipe = $stmt->fetch();

if (!$recipe) die("Not found");

// handle inline edit submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_recipe'])) {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';

    if ($title && $desc && $_SESSION['user_id'] == $recipe['user_id']) {
        $update = $pdo->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ?");
        $update->execute([$title, $desc, $id]);
        header("Location: recipe_detail.php?id=$id");
        exit;
    } else {
        echo "Error: You can't edit this or you missed a field.";
    }
}
?>

<!-- If user clicked Edit, show the form -->
<?php if (isset($_GET['edit']) && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['user_id']): ?>
    <form method="post">
        <input type="hidden" name="edit_recipe" value="1">

        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>"><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="6" cols="50"><?php echo htmlspecialchars($recipe['description']); ?></textarea><br><br>

        <input type="submit" value="Save">
        <a href="recipe_detail.php?id=<?php echo $id; ?>">Cancel</a>
    </form>

<?php else: ?>
    <!-- Normal view mode -->
    <h2>
        <?php echo htmlspecialchars($recipe['title']); ?>
        <?php if (!empty($recipe['category_name'])): ?>
            <span style="font-size: 14px; color: red; margin-left: 10px;">
                [<?php echo htmlspecialchars($recipe['category_name']); ?>]
            </span>
        <?php endif; ?>
    </h2>

    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

    <!-- ⭐ Show average rating -->
    <?php
    $avgQuery = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE recipe_id = ?");
    $avgQuery->execute([$recipe['id']]);
    $avg = $avgQuery->fetchColumn();

    if ($avg):
    ?>
        <p><strong>Average Rating:</strong> <?php echo round($avg, 1); ?> ⭐</p>
    <?php endif; ?>

    <!-- ⭐ Rating form -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="post" action="rate_recipe.php">
            <label>Rate this recipe:</label>
            <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
            <select name="rating">
                <option value="1">⭐</option>
                <option value="2">⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
            </select>
            <input type="submit" value="Rate">
        </form>
    <?php endif; ?>

    <!-- ❤️ Favorite toggle -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        $checkFav = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $checkFav->execute([$_SESSION['user_id'], $recipe['id']]);
        $isFav = $checkFav->fetch();
        ?>

        <br>
        <a href="toggle_favorite.php?id=<?php echo $recipe['id']; ?>">
            <?php if ($isFav): ?>
                ❤️ Unfavorite
            <?php else: ?>
                🤍 Favorite
            <?php endif; ?>
        </a>
    <?php endif; ?>

    <!-- ✏️ Edit / 🗑️ Delete (if owner) -->
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['user_id']): ?>
        <br><br>
        <a href="recipe_detail.php?id=<?php echo $id; ?>&edit=1">Edit</a> |
        <a href="delete_recipe.php?id=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
    <?php endif; ?>
<?php endif; ?>
