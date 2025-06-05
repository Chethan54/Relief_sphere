<?php 
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
  header("Location: admin.php");
  exit();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'disaster_relief';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['table']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Invalid request.");
}

$table = $_GET['table'];
$id = (int)$_GET['id'];

// Fetch record based on table
switch ($table) {
  case 'volunteers':
  case 'users':
  case 'incidents':
  case 'contact_messages':
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    break;
  default:
    die("Invalid table.");
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  switch ($table) {
    case 'volunteers':
      $name = $_POST['name'] ?? $data['name'];
      $availability = $_POST['availability'] ?? $data['availability'];
      $stmt = $conn->prepare("UPDATE volunteers SET name=?, availability=?, WHERE id=?");
      $stmt->bind_param("ssssi", $name,  $availability, $id);
      break;

    case 'users':
      $name = $_POST['name'] ?? $data['name'];
      $email = $_POST['email'] ?? $data['email'];
      $role = $_POST['role'] ?? $data['role'];
      $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
      $stmt->bind_param("sssi", $name, $email, $role, $id);
      break;

    case 'incidents':
      $location = $_POST['location'] ?? $data['location'];
      $description = $_POST['description'] ?? $data['description'];
      $reported_by = $_POST['reported_by'] ?? $data['reported_by'];
      $stmt = $conn->prepare("UPDATE incidents SET location=?, description=?, reported_by=? WHERE id=?");
      $stmt->bind_param("sssi", $location, $description, $reported_by, $id);
      break;

    case 'contact_messages':
      $name = $_POST['name'] ?? $data['name'];
      $email = $_POST['email'] ?? $data['email'];
      $message = $_POST['message'] ?? $data['message'];
      $stmt = $conn->prepare("UPDATE contact_messages SET name=?, email=?, message=? WHERE id=?");
      $stmt->bind_param("sssi", $name, $email, $message, $id);
      break;
  }

  $stmt->execute();
  header("Location: admin.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Record</title>
</head>
<body>
  <h2>Edit Record (<?= htmlspecialchars($table) ?>)</h2>
  <form method="POST">
    <?php
    switch ($table) {
      case 'volunteers':
        echo '
          <label>Name:</label><br>
          <input type="text" name="name" value="' . htmlspecialchars($data['name']) . '"><br>

          <label>Availability:</label><br>
          <input type="text" name="availability" value="' . htmlspecialchars($data['availability']) . '"><br>
        ';
        break;

      case 'users':
        echo '
          <label>Name:</label><br>
          <input type="text" name="name" value="' . htmlspecialchars($data['name']) . '"><br>

          <label>Email:</label><br>
          <input type="email" name="email" value="' . htmlspecialchars($data['email']) . '"><br>

          <label>Role:</label><br>
          <input type="text" name="role" value="' . htmlspecialchars($data['role']) . '"><br><br>
        ';
        break;

      case 'incidents':
        echo '
          <label>Location:</label><br>
          <input type="text" name="location" value="' . htmlspecialchars($data['location']) . '"><br>

          <label>Description:</label><br>
          <textarea name="description">' . htmlspecialchars($data['description']) . '</textarea><br>

          <label>Reported By:</label><br>
          <input type="text" name="reported_by" value="' . htmlspecialchars($data['reported_by']) . '"><br><br>
        ';
        break;

      case 'contact_messages':
        echo '
          <label>Name:</label><br>
          <input type="text" name="name" value="' . htmlspecialchars($data['name']) . '"><br>

          <label>Email:</label><br>
          <input type="email" name="email" value="' . htmlspecialchars($data['email']) . '"><br>

          <label>Message:</label><br>
          <textarea name="message">' . htmlspecialchars($data['message']) . '</textarea><br><br>
        ';
        break;
    }
    ?>
    <button type="submit">Save Changes</button>
  </form>
</body>
</html>
