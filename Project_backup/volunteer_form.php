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
  <title><?= $lang['volunteer_page_title'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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
    .volunteer-form {
      margin: auto;
      margin-top: 40px;
      padding: 30px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .form-icon { margin-right: 8px; }
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
      color: white;
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
<body>

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
      <li class="nav-item"><a class="nav-link active" href="index.php"><?= $lang['nav_home'] ?></a></li>
      <li class="nav-item"><a class="nav-link active" href="report.php"><?= $lang['nav_report'] ?></a></li>
      <li class="nav-item"><a class="nav-link active" href="resource.php"><?= $lang['nav_resources'] ?></a></li>
      <li class="nav-item"><a class="nav-link active" href="contact.php"><?= $lang['nav_contact'] ?></a></li>
    </ul>
  </div>
</nav>

<!-- Volunteer Form -->
<div class="volunteer-form">
  <h3 class="text-center text-primary mb-4">
    <i class="fas fa-hands-helping me-2"></i><?= $lang['volunteer_title'] ?>
  </h3>
  <form action="submit_volunteer.php" method="POST">

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-user form-icon"></i><?= $lang['volunteer_fullname'] ?> <span class="text-danger">*</span></label>
      <input type="text" name="name" class="form-control" placeholder="<?= $lang['volunteer_placeholder_name'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-envelope form-icon"></i><?= $lang['volunteer_email'] ?> <span class="text-danger">*</span></label>
      <input type="email" name="email" class="form-control" placeholder="<?= $lang['volunteer_placeholder_email'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-phone form-icon"></i><?= $lang['volunteer_phone'] ?> <span class="text-danger">*</span></label>
      <input type="text" name="phone" class="form-control" placeholder="<?= $lang['volunteer_placeholder_phone'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-map-marker-alt form-icon"></i><?= $lang['volunteer_city'] ?> <span class="text-danger">*</span></label>
      <input type="text" name="city" class="form-control" placeholder="<?= $lang['volunteer_placeholder_city'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-tools me-2"></i><?= $lang['volunteer_skills'] ?></label>
      <input type="text" name="skills" class="form-control" placeholder="<?= $lang['volunteer_placeholder_skills'] ?>">
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-clock me-2"></i><?= $lang['volunteer_availability'] ?></label>
      <select name="availability" class="form-select" required>
        <option value=""><?= $lang['volunteer_select_availability'] ?></option>
        <option value="Weekdays"><?= $lang['volunteer_weekdays'] ?></option>
        <option value="Weekends"><?= $lang['volunteer_weekends'] ?></option>
        <option value="Anytime"><?= $lang['volunteer_anytime'] ?></option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="fas fa-map-marker-alt me-2"></i><?= $lang['volunteer_address'] ?></label>
      <textarea name="address" rows="3" class="form-control" placeholder="<?= $lang['volunteer_placeholder_address'] ?>"></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-3">
      <i class="fas fa-paper-plane me-2"></i><?= $lang['volunteer_submit'] ?>
    </button>
  </form>
</div>

<!-- Footer -->
<footer class="custom-footer">
  <div class="social-icons">
    <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
    <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
    <a href="https://plus.google.com" target="_blank"><i class="fab fa-google"></i></a>
    <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
    <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
  </div>

  <div class="footer-bottom">
    <span>Copyright ©2025 DisasterRelief</span>
    <span>
      <a href="index.php"><?= $lang['nav_home'] ?></a>
      <a href="home.php"><?= $lang['nav_about'] ?></a>
      <a href="contact.php"><?= $lang['nav_contact'] ?></a>
      <a href="https://reliefweb.int/">Blog</a>
    </span>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
