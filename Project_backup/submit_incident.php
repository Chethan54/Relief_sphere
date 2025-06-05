<?php
include 'db.php';


$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$security_level = $_POST['security_level'] ?? '';
$latitude = (float) ($_POST['latitude'] ?? 0);
$longitude = (float) ($_POST['longitude'] ?? 0);
$address = $_POST['address'] ?? '';
$reported_by = $_POST['reported_by'] ?? '';

// PHP Validation
if (empty($title) || empty($description) || empty($security_level) || !$latitude || !$longitude || empty($reported_by)) {
  header("Location: report.php?error=" . urlencode("Please fill in all required fields."));
  exit;
}

// Insert data with reported_by
$sql = "INSERT INTO incidents (title, description, security_level, latitude, longitude, address, reported_at, reported_by)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssddss", $title, $description, $security_level, $latitude, $longitude, $address, $reported_by);

if ($stmt->execute()) {
  $message = "Incident reported successfully!";
} else {
  $message = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Report Submitted</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container text-center py-5">
    <div class="alert alert-info">
      <h4><?= htmlspecialchars($message) ?></h4>
    </div>
    <a href="report.php" class="btn btn-secondary">Report Another Incident</a>
    <a href="incidents.php" class="btn btn-primary">View All Incidents</a>
  </div>
</body>
</html>
