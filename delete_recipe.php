<?php
session_start();
require 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!isset($_SESSION['user_id'])) {
    die("You’re not logged in.");
}

if (!$id) {
    die("Missing recipe ID.");
}

// delete only if it's the user's recipe
$delete = $pdo->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
$delete->execute([$id, $_SESSION['user_id']]);

header("Location: index.php");
exit;
