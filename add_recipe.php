<?php
session_start();
require 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    die("Login to add recipes.");
}

// fetch categories
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();

// fetch ingredients
$allIngredients = $pdo->query("SELECT * FROM ingredients")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $catId = $_POST['category_id'] ?? null;
    $uid = $_SESSION['user_id'];

    if ($title && $desc && $catId) {
        // insert recipe
        $add = $pdo->prepare("INSERT INTO recipes (user_id, title, description, category_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $add->execute([$uid, $title, $desc, $catId]);
        $recipeId = $pdo->lastInsertId();

        // insert selected ingredients
        if (!empty($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $ingId => $val) {
                $amount = $_POST['amounts'][$ingId] ?? '';
                if ($amount) {
                    $stmt = $pdo->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, amount) VALUES (?, ?, ?)");
                    $stmt->execute([$recipeId, $ingId, $amount]);
                }
            }
        }

        // insert steps
        if (!empty($_POST['steps'])) {
            foreach ($_POST['steps'] as $num => $text) {
                if (trim($text)) {
                    $stmt = $pdo->prepare("INSERT INTO steps (recipe_id, step_number, instruction) VALUES (?, ?, ?)");
                    $stmt->execute([$recipeId, $num, trim($text)]);
                }
            }
        }

        header("Location: index.php");
        exit;
    } else {
        echo "Please fill everything.";
    }
}
?>

<h2>Add New Recipe</h2>

<form method="post">
    <label>Title:</label><br>
    <input type="text" name="title"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="6" cols="50"></textarea><br><br>

    <label>Category:</label><br>
    <select name="category_id">
        <option value="">-- Choose a category --</option>
        <?php foreach ($cats as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Ingredients:</label><br>
    <?php foreach ($allIngredients as $ing): ?>
        <input type="checkbox" name="ingredients[<?php echo $ing['id']; ?>]" value="1">
        <?php echo htmlspecialchars($ing['name']); ?>
        <input type="text" name="amounts[<?php echo $ing['id']; ?>]" placeholder="Amount"><br>
    <?php endforeach; ?>
    <br>

    <label>Steps:</label><br>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <textarea name="steps[<?php echo $i; ?>]" rows="2" cols="60" placeholder="Step <?php echo $i; ?>"></textarea><br><br>
    <?php endfor; ?>

    <input type="submit" value="Post Recipe">
</form>
