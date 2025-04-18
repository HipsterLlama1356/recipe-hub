<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

// check if logged in
if (!isset($_SESSION['user_id'])) {
    die("Login first.");
}

// handle adding new ingredient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['new_ingredient'])) {
    $name = trim($_POST['new_ingredient']);
    if ($name) {
        $add = $pdo->prepare("INSERT INTO ingredients (name) VALUES (?)");
        $add->execute([$name]);
        header("Location: manage_ingredients.php");
        exit;
    }
}

// handle deleting
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $del = $pdo->prepare("DELETE FROM ingredients WHERE id = ?");
    $del->execute([$id]);
    header("Location: manage_ingredients.php");
    exit;
}

// get all ingredients
$ingredients = $pdo->query("SELECT * FROM ingredients ORDER BY name")->fetchAll();
?>

<h2>🧂 Manage Ingredients</h2>

<!-- Add form -->
<form method="post">
    <input type="text" name="new_ingredient" placeholder="New ingredient name">
    <input type="submit" value="Add">
</form>

<hr>

<!-- List of ingredients -->
<ul>
    <?php foreach ($ingredients as $ing): ?>
        <li>
            <?php echo htmlspecialchars($ing['name']); ?>
            <a href="?delete=<?php echo $ing['id']; ?>" onclick="return confirm('Delete this ingredient?')">❌</a>
        </li>
    <?php endforeach; ?>
</ul>
