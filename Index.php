<?php
// connect to database
require 'includes/db.php';

// get latest recipes
$grabRecipes = $pdo->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 10");
$recipes = $grabRecipes->fetchAll();
?>

<h2>New Recipes</h2>

<?php foreach ($recipes as $r): ?>
    <div style="margin-bottom: 20px;">
        <h3><?php echo htmlspecialchars($r['title']); ?></h3>
        <p><?php echo htmlspecialchars(substr($r['description'], 0, 100)); ?>...</p>
        <a href="recipe_detail.php?id=<?php echo $r['id']; ?>">View Recipe</a>
    </div>
    <hr>
<?php endforeach; ?>
