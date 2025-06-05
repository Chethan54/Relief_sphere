<?php
// donation_process.php

include 'db.php';

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name'], $data['email'], $data['amount'])) {
    http_response_code(400);
    echo "Invalid donation data.";
    exit;
}

$name = mysqli_real_escape_string($conn, $data['name']);
$email = mysqli_real_escape_string($conn, $data['email']);
$amount = floatval($data['amount']);
$message = mysqli_real_escape_string($conn, $data['message'] ?? '');
$payment_id = mysqli_real_escape_string($conn, $data['payment_id'] ?? 'UPI-Manual');
$donation_date = date("Y-m-d H:i:s");

// Insert into donations table
$sql = "INSERT INTO donations (name, email, amount, message, payment_id, donation_date)
        VALUES ('$name', '$email', '$amount', '$message', '$payment_id', '$donation_date')";

if (mysqli_query($conn, $sql)) {
    echo "Thank you! Your donation has been recorded.";
} else {
    http_response_code(500);
    echo "Error saving donation: " . mysqli_error($conn);
}
?>
