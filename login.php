<?php
// start the session to log users in
session_start();

// connect to db
require 'includes/db.php';

// check if login form was used
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';

    // get user from db
    $getUser = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $getUser->execute([$email]);
    $user = $getUser->fetch();

    // check if user exists and password is correct
    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "You’re in!";
        // redirect if you want: header('Location: index.php');
    } else {
        echo "Wrong email or password.";
    }
}
?>

<!-- login form thing -->
<form method="post">
    <label>Email:</label>
    <input type="email" name="email"><br><br>

    <label>Password:</label>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Login">
</form>
