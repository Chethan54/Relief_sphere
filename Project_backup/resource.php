<?php
session_start();
include 'language_loader.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: logins.php");
  exit;
}

// Include DB connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "disaster_relief";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$successMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $resource_type = $_POST['resource_type'];
  $quantity = $_POST['quantity'];
  $location = $_POST['location'];
  $submitted_by = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO resource_collection (resource_type, quantity, location, submitted_by) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sisi", $resource_type, $quantity, $location, $submitted_by);

  if ($stmt->execute()) {
    $successMsg = "✅ " . $lang['resource_submit_success'];
  } else {
    $successMsg = "❌ " . $lang['error'] . ": " . $conn->error;
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="<?= $lang['html_lang'] ?? 'en' ?>">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($lang['nav_resources']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>

  <style>
    nav {
      position: relative;
      z-index: 1;
      height: 70px;
      font-size: 20px;
    }
    nav ul {
      margin: 0;
      padding: 0;
      font-size: 20px;
      list-style: none;
      display: flex;
      gap: 20px;
    }
    .navbar-nav .nav-link {
      font-weight: 600;
    }
    .nav-link:hover {
      color: #00bfff !important;
    }
    .navbar-brand {
      color: white;
      font-weight: bold;
    }
    .custom-footer {
      background-color: #111;
      color: #fff;
      text-align: center;
      padding: 40px 20px 20px;
      font-family: 'Segoe UI', sans-serif;
    }
    .custom-footer h2 {
      font-weight: 600;
      margin-bottom: 10px;
    }
    .custom-footer p {
      max-width: 600px;
      margin: auto;
      font-size: 14px;
      line-height: 1.6;
      color: #ccc;
    }
    .custom-footer .social-icons {
      margin: 20px 0;
    }
    .custom-footer .social-icons a {
      display: inline-block;
      margin: 0 10px;
      color: #fff;
      background: #222;
      padding: 10px;
      border-radius: 50%;
      transition: background 0.3s;
    }
    .custom-footer .social-icons a:hover {
      background: #007bff;
    }
    .custom-footer .footer-bottom {
      margin-top: 30px;
      border-top: 1px solid #444;
      padding-top: 10px;
      font-size: 13px;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .custom-footer .footer-bottom a {
      color: rgb(250, 252, 255);
      margin-left: 10px;
      text-decoration: none;
    }
    @media (max-width: 576px) {
      .custom-footer .footer-bottom {
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }
    }
  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand d-flex align-items-center gap-2">
  <img src="logo.jpeg" alt="Logo" width="40" height="35" class="d-inline-block align-text-top rounded-circle">
  <?= $lang['site_title'] ?? 'Relief Sphere' ?>
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php"><?= htmlspecialchars($lang['nav_home']) ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="report.php"><?= htmlspecialchars($lang['nav_report']) ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="volunteer_form.php"><?= htmlspecialchars($lang['nav_volunteer']) ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php"><?= htmlspecialchars($lang['nav_contact']) ?></a></li>
      </ul>
    </div>
  </nav>

  <div class="container my-5">
    <div class="card shadow rounded-4">
      <div class="card-body p-4">
        <h3 class="mb-4 text-primary"><i class="fas fa-boxes"></i> <?= htmlspecialchars($lang['nav_resources']) ?></h3>

        <!-- Success Message -->
        <?php if (!empty($successMsg)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $successMsg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="">
          <!-- Resource Type -->
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-cubes me-2"></i><?= htmlspecialchars($lang['resource_type']) ?></label>
            <select name="resource_type" class="form-select" required>
              <option value=""><?= htmlspecialchars($lang['select']) ?></option>
              <option value="Food">🍽️ <?= htmlspecialchars($lang['food']) ?></option>
              <option value="Water">💧 <?= htmlspecialchars($lang['water']) ?></option>
              <option value="Medicine">💊 <?= htmlspecialchars($lang['medicine']) ?></option>
              <option value="Shelter Kits">🏕️ <?= htmlspecialchars($lang['shelter_kits']) ?></option>
              <option value="Clothing">👕 <?= htmlspecialchars($lang['clothing']) ?></option>
            </select>
          </div>

          <!-- Quantity -->
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-sort-numeric-up me-2"></i><?= htmlspecialchars($lang['quantity']) ?></label>
            <input type="number" name="quantity" class="form-control" min="1" required placeholder="<?= htmlspecialchars($lang['enter_quantity']) ?>">
          </div>

          <!-- Location -->
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($lang['location']) ?></label>
            <input type="text" name="location" class="form-control" required placeholder="<?= htmlspecialchars($lang['enter_location']) ?>">
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane me-2"></i><?= htmlspecialchars($lang['submit']) ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="custom-footer">
    <h2><?= htmlspecialchars($lang['footer_title']) ?></h2>
    <p><?= htmlspecialchars($lang['footer_description']) ?></p>

    <div class="social-icons">
      <a href="https://facebook.com" target="_blank"><i class="bi bi-facebook"></i></a>
      <a href="https://twitter.com" target="_blank"><i class="bi bi-twitter"></i></a>
      <a href="https://plus.google.com" target="_blank"><i class="bi bi-google"></i></a>
      <a href="https://youtube.com" target="_blank"><i class="bi bi-youtube"></i></a>
      <a href="https://linkedin.com" target="_blank"><i class="bi bi-linkedin"></i></a>
    </div>

    <div class="footer-bottom">
      <span><?= htmlspecialchars($lang['copyright']) ?></span>
      <span>
        <a href="index.php"><?= htmlspecialchars($lang['nav_home']) ?></a>
        <a href="home.php"><?= htmlspecialchars($lang['nav_about']) ?></a>
        <a href="contact.php"><?= htmlspecialchars($lang['nav_contact']) ?></a>
        <a href="https://reliefweb.int/"><?= htmlspecialchars($lang['blog']) ?></a>
      </span>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
