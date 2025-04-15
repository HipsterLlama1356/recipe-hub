<?php
// connect to database
require 'includes/db.php';

// check if form was posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // get data from form
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $rawPass = $_POST['password'] ?? '';

    // hash password
    $hashed = password_hash($rawPass, PASSWORD_DEFAULT);

    // make sure stuff isn’t empty
    if ($username && $email && $rawPass) {
        // add new user to db
        $addUser = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $addUser->execute([$username, $email, $hashed]);
        echo "You’re signed up!";
    } else {
        echo "Fill in all fields pls.";
    }
}
?>

<!-- very basic form -->
<form method="post">
    <label>Username:</label>
    <input type="text" name="username"><br><br>

    <label>Email:</label>
    <input type="email" name="email"><br><br>

    <label>Password:</label>
    <input type="password" name="password"><br><br>

    <input type="submit" value="Sign up">
</form>