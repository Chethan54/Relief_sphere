<?php
session_start();
include 'language_loader.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: logins.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['report_incident_title'] ?? 'Report New Incident' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

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
    #map {
      height: 300px;
    }
    body {
      background-color: rgb(154, 115, 115);
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
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
  <li class="nav-item"><a class="nav-link active" href="index.php"><?= $lang['home'] ?? 'Home' ?></a></li>
  <li class="nav-item"><a class="nav-link active" href="volunteer_form.php"><?= $lang['volunteer'] ?? 'Volunteer' ?></a></li>
  <li class="nav-item"><a class="nav-link active" href="resource.php"><?= $lang['resource_collection'] ?? 'Resource Collection' ?></a></li>
  <li class="nav-item"><a class="nav-link active" href="contact.php"><?= $lang['contact'] ?? 'Contact' ?></a></li>
</ul>
  </div>
</nav>

<!-- Incident Form -->
<div class="container py-4">
  <h3 class="mb-4 text-primary"><?= $lang['report_incident_title'] ?? 'Report New Incident' ?></h3>
  <p><?= $lang['report_incident_description'] ?? 'Provide details about the disaster incident and select its location on the map.' ?></p>

  <div id="form-container">
    <form id="incidentForm" method="POST" action="submit_incident.php" onsubmit="return handleSubmit()">
      <input type="hidden" name="reported_by" value="<?= $_SESSION['user_id'] ?>">

      <div class="mb-3">
        <label class="form-label"><?= $lang['incident_title'] ?? 'Incident Title' ?> <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" placeholder="E.g., Flooding in Downtown" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= $lang['description'] ?? 'Description' ?> <span class="text-danger">*</span></label>
        <textarea name="description" class="form-control" rows="4" placeholder="Provide details about the incident..." required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= $lang['security_level'] ?? 'Security Level' ?> <span class="text-danger">*</span></label>
        <select name="security_level" class="form-select" required>
          <option value=""><?= $lang['select_level'] ?? 'Select Level' ?></option>
          <option value="Low"><?= $lang['low_level'] ?? 'Low - Minor impact' ?></option>
          <option value="Medium"><?= $lang['medium_level'] ?? 'Medium - Moderate impact, several areas affected' ?></option>
          <option value="High"><?= $lang['high_level'] ?? 'High - Major impact, immediate response needed' ?></option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= $lang['location'] ?? 'Location' ?> <span class="text-danger">*</span></label>
        <p><?= $lang['click_map_instruction'] ?? 'Click on the map to select a location for the new incident.' ?></p>
        <div id="map"></div>
      </div>

      <div class="row mt-3">
        <div class="col-md-4 mb-3">
          <label class="form-label">Latitude</label>
          <input type="text" id="lat" name="latitude" class="form-control" readonly required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Longitude</label>
          <input type="text" id="lng" name="longitude" class="form-control" readonly required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Address</label>
          <input type="text" id="address" name="address" class="form-control" readonly>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 mt-3"><?= $lang['submit_incident'] ?? 'Submit Incident Report' ?></button>

      <div id="loadingSpinner" class="text-center py-4" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-3"><?= $lang['submitting'] ?? 'Submitting your report...' ?></p>
      </div>

      <div id="successMessage" class="alert alert-success mt-4 text-center" style="display:none; animation: fadeIn 0.8s ease-in-out;">
        ✅ <?= $lang['success_message'] ?? 'Incident submitted successfully!' ?>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<footer class="custom-footer">
  <h2><?= $lang['footer_title'] ?? 'About Us' ?></h2>
  <p><?= $lang['footer_description'] ?? 'Our platform helps report, track, and respond to disaster incidents in real time, promoting rapid action and recovery with community involvement.' ?></p>

  <div class="social-icons">
    <a href="https://facebook.com" target="_blank"><i class="bi bi-facebook"></i></a>
    <a href="https://twitter.com" target="_blank"><i class="bi bi-twitter"></i></a>
    <a href="https://plus.google.com" target="_blank"><i class="bi bi-google"></i></a>
    <a href="https://youtube.com" target="_blank"><i class="bi bi-youtube"></i></a>
    <a href="https://linkedin.com" target="_blank"><i class="bi bi-linkedin"></i></a>
  </div>

  <div class="footer-bottom">
    <span><?= $lang['copyright'] ?? '© 2025 Relief Sphere. All rights reserved.' ?></span>
    <span>
      <a href="index.php"><?= $lang['home'] ?? 'Home' ?></a>
      <a href="home.php"><?= $lang['about'] ?? 'About' ?></a>
      <a href="contact.php"><?= $lang['contact'] ?? 'Contact' ?></a>
      <a href="https://reliefweb.int/">Blog</a>
    </span>
  </div>
</footer>

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  var map = L.map('map').setView([10.81, 79.28], 8);
  map.options.minZoom = 5;
  var marker;

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  function getAddress(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('address').value = data.display_name || "Not found";
      });
  }

  map.on('click', function(e) {
    var lat = e.latlng.lat.toFixed(6);
    var lng = e.latlng.lng.toFixed(6);
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
    getAddress(lat, lng);

    if (marker) {
      map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
  });

  function handleSubmit() {
    const form = document.getElementById('incidentForm');
    const spinner = document.getElementById('loadingSpinner');

    const requiredFields = ["title", "description", "security_level", "latitude", "longitude"];
    for (const name of requiredFields) {
      const value = form[name].value.trim();
      if (!value) {
        alert("Please fill all required fields.");
        return false;
      }
    }

    spinner.style.display = "block";
    return true;
  }
</script>
</body>
</html>
