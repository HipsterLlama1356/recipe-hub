<?php
include 'includes/header.php';

session_start();
require 'includes/db.php';

// stop users who ain't logged in
if (!isset($_SESSION['user_id'])) {
    die("Log in first to post stuff.");
}

// if user posted the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $uid = $_SESSION['user_id'];

    // basic check
    if ($title && $desc) {
        $add = $pdo->prepare("INSERT INTO recipes (user_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
        $add->execute([$uid, $title, $desc]);
        echo "Recipe added!";
    } else {
        echo "You forgot something...";
    }
}
?>

<!-- basic form for adding recipe -->
<form method="post">
    <label>Title:</label><br>
    <input type="text" name="title"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" cols="40"></textarea><br><br>

    <input type="submit" value="Post Recipe">
</form>
