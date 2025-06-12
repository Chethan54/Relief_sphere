<?php
// Database credentials
$host     = "sql313.infinityfree.com";      // or your server IP
$username = "if0_39155453";           // default for XAMPP
$password = "Tharun09";               // default for XAMPP
$database = "if0_39155453_XXX";     // your database name

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
