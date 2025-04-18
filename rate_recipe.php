<?php
session_start();
require 'includes/db.php';


$uid = $_SESSION['user_id'] ?? null;
$rid = $_POST['recipe_id'] ?? null;
$stars = $_POST['rating'] ?? null;

if ($uid && $rid && $stars >= 1 && $stars <= 5) {
    // remove previous rating if it exists
    $pdo->prepare("DELETE FROM ratings WHERE user_id = ? AND recipe_id = ?")->execute([$uid, $rid]);

    // insert new one
    $stmt = $pdo->prepare("INSERT INTO ratings (user_id, recipe_id, rating) VALUES (?, ?, ?)");
    $stmt->execute([$uid, $rid, $stars]);
}

header("Location: recipe_detail.php?id=$rid");
exit;
