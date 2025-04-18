<?php
// database info
$server = 'localhost';
$database = 'recipe_hub';
$username = 'root';
$password = '';
$encoding = 'utf8mb4';

// build connection string
$connectString = "mysql:host=$server;dbname=$database;charset=$encoding";

// settings to keep it secure and useful
$settings = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // show detailed DB errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // return clean arrays
    PDO::ATTR_EMULATE_PREPARES => false // better real SQL security
];

try {
    // make the connection
    $pdo = new PDO($connectString, $username, $password, $settings);
} catch (PDOException $error) {
    // show error and stop if it fails
    die("Connection Failed: " . $error->getMessage());
}
?>
