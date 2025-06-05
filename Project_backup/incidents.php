<?php
include 'language_loader.php';
$host = "localhost";
$user = "root";
$password = "";
$dbname = "disaster_relief";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ✅ Keep only this correct JOIN query
$sql = "
  SELECT i.*, u.name AS reporter_name
  FROM incidents i
  LEFT JOIN users u ON i.reported_by = u.id
  ORDER BY i.reported_at DESC
";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Reported Incidents</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-4">
    <h2 class="mb-4">All Reported Incidents</h2>
    <a href="index.php" class="btn btn-secondary mb-3">⬅ Back to Home</a>

    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Description</th>
              <th>Security Level</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Address</th>
              <th>Reported At</th>
              <th>Reported By</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row["id"] ?></td>
                <td><?= htmlspecialchars($row["title"]) ?></td>
                <td><?= htmlspecialchars($row["description"]) ?></td>
                <td><?= $row["security_level"] ?></td>
                <td><?= $row["latitude"] ?></td>
                <td><?= $row["longitude"] ?></td>
                <td><?= htmlspecialchars($row["address"]) ?></td>
                <td><?= $row["reported_at"] ?></td>
              <td><?= htmlspecialchars($row["reporter_name"]) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="alert alert-warning">No incidents reported yet.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
  </div>
</body>
</html>
