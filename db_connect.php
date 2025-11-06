<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password (often empty)
$dbname = "awana_food_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop execution and display connection error
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
$conn->set_charset("utf8mb4");
?>