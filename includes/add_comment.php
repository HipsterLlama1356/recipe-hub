<?php
session_start();
require 'includes/db.php';

// make sure logged in
if (!isset($_SESSION['user_id'])) {
    die("Log in to comment.");
}

// get recipe id
$recipeId = $_POST['recipe_id'] ?? null;
$commentText = $_POST['comment'] ?? '';
$userId = $_SESSION['user_id'];

// make sure it's not empty
if ($recipeId && $commentText) {
    $add = $pdo->prepare("INSERT INTO comments (user_id, recipe_id, content, created_at) VALUES (?, ?, ?, NOW())");
    $add->execute([$userId, $recipeId, $commentText]);
    echo "Comment posted!";
} else {
    echo "Missing something...";
}
?>
