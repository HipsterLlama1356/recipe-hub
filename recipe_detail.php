<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) die("No ID given.");

$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
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

<!-- Show editable form if ?edit=1 -->
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
    <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['user_id']): ?>
        <a href="recipe_detail.php?id=<?php echo $id; ?>&edit=1">Edit</a>
    <?php endif; ?>
<?php endif; ?>
