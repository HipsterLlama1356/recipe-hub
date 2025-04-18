<?php
session_start();

require 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    die("Login to add recipes.");
}

// fetch categories
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $catId = $_POST['category_id'] ?? null;
    $uid = $_SESSION['user_id'];

    if ($title && $desc && $catId) {
        $add = $pdo->prepare("INSERT INTO recipes (user_id, title, description, category_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $add->execute([$uid, $title, $desc, $catId]);
        header("Location: index.php");
        exit;
    } else {
        echo "Please fill everything.";
    }
}
?>

<!-- Add Recipe Form -->
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

    <input type="submit" value="Post Recipe">
</form>
