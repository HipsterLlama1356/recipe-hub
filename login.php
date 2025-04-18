<?php
include 'includes/header.php';

session_start();
require 'includes/db.php';

// check if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    // get user from db
    $getUser = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $getUser->execute([$email]);
    $user = $getUser->fetch();

    // DEBUG OUTPUT – see what's going on
    echo "<pre>";
    print_r($user);
    echo "</pre>";

    echo "Typed password: " . $pass . "<br>";
    echo "DB password: " . ($user['password'] ?? 'N/A') . "<br>";

    // check if user exists and password is correct
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        echo "Wrong email or password.";
    }
}
?>

<!-- login form thing -->
<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Login">
</form>
