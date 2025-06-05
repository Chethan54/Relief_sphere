<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "disaster_relief";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$skills = $_POST['skills'];

$sql = "INSERT INTO volunteers (name, email, phone, city, skills) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $name, $email, $phone, $city, $skills);

if ($stmt->execute()) {
  $message = "Thank You Be A Volunteer!";
} else {
  $message = "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <title>Volunteer Submitted</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container text-center py-5">
    <div class="alert alert-info">
      <h4><?= htmlspecialchars($message) ?></h4>
    </div>
    <a href="index.php" class="btn btn-secondary mb-3">⬅ Back to Home</a>  </div>
</body>
</html>



