<?php
require 'includes/db.php';

// get recipe id from URL
$rid = $_GET['id'] ?? null;

// check if there's an ID
if (!$rid) {
    die("No recipe picked.");
}

// get the recipe from db
$grab = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$grab->execute([$rid]);
$recipe = $grab->fetch();

// if nothing found
if (!$recipe) {
    die("Couldn't find recipe.");
}
?>

<!-- show the recipe -->
<h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
<p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

<a href="index.php">← Go Back</a>

<?php if (isset($_SESSION['user_id'])): ?>
    <h3>Leave a Comment</h3>
    <form method="post" action="add_comment.php">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
        <textarea name="comment" placeholder="Write something..." rows="3" cols="40"></textarea><br>
        <input type="submit" value="Post Comment">
    </form>
<?php else: ?>
    <p><a href="login.php">Log in</a> to comment.</p>
<?php endif; ?>
