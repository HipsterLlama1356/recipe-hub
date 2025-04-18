<?php
// basic connection to the database


$server = 'localhost';
$database = 'recipe_hub';
$username = 'root';
$password = '';
$encoding = 'utf8mb4';

// connection string thingy
$connectString = "mysql:host=$server;dbname=$database;charset=$encoding";

// some settings for how it runs
$settings = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // clean results
    PDO::ATTR_EMULATE_PREPARES => false, // better security
];

try {
    // make the actual connection here
    $pdo = new PDO($connectString, $username, $password, $settings);
} catch (PDOException $error) {
    // show error if it fails
    die("Connection Failed: " . $error->getMessage());
}
?>