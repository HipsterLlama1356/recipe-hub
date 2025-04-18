<?php
include 'includes/header.php';
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $rawPass = $_POST['password'] ?? '';

    $hashed = password_hash($rawPass, PASSWORD_DEFAULT);

    if ($username && $email && $rawPass) {
        $addUser = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $addUser->execute([$username, $email, $hashed]);
        echo "You’re signed up!";
    } else {
        echo "Fill in all fields pls.";
    }
}
?>

<form method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>

    <label>Email:</label>
    <input type="email" name="email"><br><br>

    <label>Password:</label>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Sign up">
</form>
