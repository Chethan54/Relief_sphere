<?php
// Database credentials
$host     = "localhost";      // or your server IP
$username = "root";           // default for XAMPP
$password = "";               // default for XAMPP
$database = "disaster_relief";     // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8");

// echo "✅ Connected to database successfully";
?> 