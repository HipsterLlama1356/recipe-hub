<?php
session_start();
require 'includes/db.php';


$uid = $_SESSION['user_id'] ?? null;
$rid = $_GET['id'] ?? null;

if (!$uid || !$rid) {
    die("Unauthorized.");
}

// check if already favorited
$check = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND recipe_id = ?");
$check->execute([$uid, $rid]);
$exists = $check->fetch();

if ($exists) {
    // unfavorite
    $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?")->execute([$uid, $rid]);
} else {
    // favorite
    $pdo->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)")->execute([$uid, $rid]);
}

header("Location: recipe_detail.php?id=$rid");
exit;
