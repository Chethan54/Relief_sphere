<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'disaster_relief';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch data
$contact_messages = $conn->query("SELECT * FROM contact_messages ORDER BY sent_at DESC");
$incidents = $conn->query("
  SELECT i.id, i.address AS location, i.description, i.reported_at, u.name AS reporter_name
  FROM incidents i
  LEFT JOIN users u ON i.reported_by = u.id
  ORDER BY i.reported_at DESC
");
$volunteers = $conn->query("SELECT * FROM volunteers ORDER BY registered_at DESC");
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$resources = $conn->query("SELECT * FROM resource_collection ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Basic Styling */
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f1f3f5;
      display: flex;
    }

    .navbar {
      background-color:rgb(12, 12, 12);
      color: white;
      padding: 15px 2px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .navbar h1 {
      margin: 0;
      font-size: 25px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .toggle-btn {
      font-size: 22px;
      cursor: pointer;
      margin-right: 20px;
    }

    .navbar .nav-links a {
      color: white;
      margin-left: 30px;
      text-decoration: none;
      font-size: 16px;
    }

    .sidebar {
      width: 200px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      height: 100vh;
      margin-top: 64px;
      position: fixed;
      left: 0;
      top: 0;
      transition: transform 0.3s ease;
      transform: translateX(0);
      z-index: 999;
    }

    .sidebar.collapsed {
      transform: translateX(-100%);
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      padding: 12px;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.2s;
    }

    .sidebar ul li:hover {
      background-color: #34495e;
    }

    .sidebar i {
      margin-right: 10px;
    }

    .main-content {
      flex-grow: 1;
      padding: 100px 20px 20px 20px;
      margin-left: 220px;
      transition: margin-left 0.3s ease;
    }

    .main-content.collapsed {
      margin-left: 0;
    }

    h2 {
      margin-top: 40px;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      margin-bottom: 40px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }

    th {
      background:rgb(93, 100, 107);
      color: white;
    }

    tr:nth-child(even) {
      background: #f2f2f2;
    }

    a {
      color: #007bff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .section {
      display: none;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar collapsed" id="sidebar">
    <ul>
      <li onclick="showSection('users')"><i class="fas fa-users" aria-hidden="true"></i> Users</li>
      <li onclick="showSection('messages')"><i class="fas fa-envelope" aria-hidden="true"></i> Contact Messages</li>
      <li onclick="showSection('incidents')"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Incidents</li>
      <li onclick="showSection('volunteers')"><i class="fas fa-hands-helping" aria-hidden="true"></i> Volunteers</li>
      <li onclick="showSection('resources')"><i class="fas fa-boxes" aria-hidden="true"></i> Resources</li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content collapsed" id="mainContent">

    <div class="navbar">
      <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars" aria-hidden="true"></i>
      </div>
      <h1><i class="fas fa-tachometer-alt" aria-hidden="true"></i> Admin Dashboard</h1>
      <div class="nav-links">
        <a href="export.php"><i class="fas fa-file-export" aria-hidden="true"></i> Export</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout</a>
      </div>
    </div>

    <!-- Users Table -->
    <div id="users" class="section">
      <h2><i class="fas fa-users" aria-hidden="true"></i> Users</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created At</th></tr>
        </thead>
        <tbody>
          <?php while($row = $users->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td><?= $row['created_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

 <!-- Contact Messages Table -->
<div id="messages" class="section">
  <h2><i class="fas fa-envelope"></i> Contact Messages</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>First Name</th><th>Email</th><th>Message</th><th>Sent At</th></tr>
    </thead>
    <tbody>
<?php while($row = $contact_messages->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <?php
   
    ?>
    <td><?= htmlspecialchars($row['first_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['message']) ?></td>
    <td><?= $row['sent_at'] ?></td>
  </tr>
<?php endwhile; ?>
</tbody>

  </table>
</div>
    <!-- Incidents Table -->
    <div id="incidents" class="section">
  <h2><i class="fas fa-exclamation-triangle"></i> Incidents</h2>
  <table>
    <thead>
      <tr><th>ID</th><th>Address</th><th>Description</th><th>Reported By</th><th>Reported At</th></tr>
    </thead>
    <tbody>
    <?php while($row = $incidents->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= isset($row['location']) ? htmlspecialchars($row['location']) : 'N/A' ?></td>
    <td><?= isset($row['description']) ? htmlspecialchars($row['description']) : 'N/A' ?></td>
    <td><?= isset($row['reporter_name']) ? htmlspecialchars($row['reporter_name']) : 'N/A' ?></td>
    <td><?= $row['reported_at'] ?></td>
  </tr>
<?php endwhile; ?>

    </tbody>
  </table>
</div>


    <!-- Volunteers Table -->
    <div id="volunteers" class="section">
      <h2><i class="fas fa-hands-helping" aria-hidden="true"></i> Registered Volunteers</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Skills</th><th>Registered At</th></tr>
        </thead>
        <tbody>
          <?php while($row = $volunteers->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['skills']) ?></td>
              <td><?= $row['registered_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Resource Collection Table -->
    <div id="resources" class="section">
      <h2><i class="fas fa-boxes" aria-hidden="true"></i> Resource Collection</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Type</th><th>Quantity</th><th>Location</th><th>Submitted At</th></tr>
        </thead>
        <tbody>
          <?php while($row = $resources->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['resource_type']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= htmlspecialchars($row['location']) ?></td>
              <td><?= $row['submitted_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Show selected section
    function showSection(sectionId) {
      document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
      });
      document.getElementById(sectionId).style.display = 'block';
    }

    // Toggle sidebar
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('mainContent').classList.toggle('collapsed');
    }

    // On load
    window.onload = function () {
      showSection('users');
    };
  </script>

</body>
</html>
