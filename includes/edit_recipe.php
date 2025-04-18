<?php
session_start(); // must go first
require 'includes/db.php';
include 'includes/header.php';

// make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You're not logged in.");
}

// get the recipe id
$rid = $_GET['id'] ?? null;
if (!$rid) {
    die("No recipe ID.");
}

// get recipe from db
$check = $pdo->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
$check->execute([$rid, $_SESSION['user_id']]);
$recipe = $check->fetch();

// stop if not found or not yours
if (!$recipe) {
    die("Not your recipe or it doesn’t exist.");
}

// update form logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = $_POST['title'] ?? '';
    $newDesc = $_POST['description'] ?? '';

    if ($newTitle && $newDesc) {
        $update = $pdo->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ?");
        $update->execute([$newTitle, $newDesc, $rid]);
        header("Location: recipe_detail.php?id=$rid");
        exit;
    } else {
        echo "Fill it out fully.";
    }
}
?>

<!-- edit form prefilled -->
<form method="post">
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" cols="40"><?php echo htmlspecialchars($recipe['description']); ?></textarea><br><br>

    <input type="submit" value="Update Recipe">
</form>
