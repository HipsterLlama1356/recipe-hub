<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Styled Navbar -->
<div style="background: #333; color: white; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
    <!-- Left side -->
    <div>
        <a href="index.php" style="color:white; margin-right: 15px;">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="color:white;"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <?php endif; ?>
    </div>

    <!-- Right side -->
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="add_recipe.php" style="color:white; margin-right: 15px;">Add Recipe</a>
            <a href="manage_ingredients.php" style="color:white; margin-right: 15px;">Ingredients</a>
            <a href="logout.php" style="color:white;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color:white; margin-right: 15px;">Login</a>
            <a href="register.php" style="color:white;">Register</a>
        <?php endif; ?>
    </div>
</div>
<hr>
