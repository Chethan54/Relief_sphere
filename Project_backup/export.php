<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'disaster_relief';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="volunteers.csv"');

$output = fopen("php://output", "w");

// Adjust the column names based on your actual table structure
fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'City', 'Skills', 'Availability', 'Registered At']);

$result = $conn->query("SELECT id, name, email, phone, city, skills, availability, registered_at FROM volunteers");

while($row = $result->fetch_assoc()) {
  fputcsv($output, $row);
}

fclose($output);
?>
