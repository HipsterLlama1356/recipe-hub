<?php
// FIX: session_start BEFORE anything else
session_start();
require 'includes/db.php';

// make sure logged in
if (!isset($_SESSION['user_id'])) {
    die("Log in to comment.");
}

$recipeId = $_POST['recipe_id'] ?? null;
$commentText = $_POST['comment'] ?? '';
$userId = $_SESSION['user_id'];

// check if filled
if ($recipeId && $commentText) {
    $add = $pdo->prepare("INSERT INTO comments (user_id, recipe_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $add->execute([$userId, $recipeId, $commentText]);

    // 🔄 redirect back to recipe
    header("Location: recipe_detail.php?id=$recipeId");
    exit;
} else {
    echo "Missing something...";
}
?>
