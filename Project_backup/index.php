<?php
session_start();
include 'language_loader.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: logins.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['title_disaster_relief_platform'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    nav{
      position: relative;
      z-index: 1;
      height: 70px;
      font-size:20px;
    }
    nav ul{
      margin: 0;
      padding: 0;
      font-size: 20px;
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .hero {
      background: url('https://images.pexels.com/photos/942560/pexels-photo-942560.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1') no-repeat center center/cover;
      height: 90vh;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
    }

    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .section-title {
      margin-bottom: 2rem;
      color: #004080;
    }

    footer {
      background-color:rgb(0, 0, 0);
      color: white;
      padding: 1rem 0;
      text-align: center;
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
      color:rgb(255, 255, 255);
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
    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <img src="logo.jpeg" alt="<?= $lang['alt_logo'] ?>" width="40" height="35" class="d-inline-block align-text-top rounded-circle">
      <?= $lang['relief_sphere'] ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="home.php"><?= $lang['about_us'] ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="report.php"><?= $lang['report_incident'] ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="volunteer_form.php"><?= $lang['volunteer'] ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="resource.php"><?= $lang['resource_collection'] ?></a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php"><?= $lang['contact'] ?></a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-info" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa fa-user-circle me-1"></i><?= $_SESSION['user_name'] ?? $lang['profile'] ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i><?= $lang['logout'] ?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1 class="display-4"><?= $lang['hero_title'] ?></h1>
      <p class="lead"><?= $lang['hero_subtitle'] ?></p>
    </div>
  </section>

  <!-- Features -->
  <section class="container py-5">
    <h2 class="section-title text-center"><?= $lang['key_features'] ?></h2>
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <i class="fas fa-bell fa-2x text-primary mb-2"></i>
        <h4><?= $lang['feature_realtime_alerts_title'] ?></h4>
        <p><?= $lang['feature_realtime_alerts_desc'] ?></p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-hands-helping fa-2x text-success mb-2"></i>
        <h4><?= $lang['feature_volunteer_system_title'] ?></h4>
        <p><?= $lang['feature_volunteer_system_desc'] ?></p>
      </div>
      <div class="col-md-4 mb-4">
        <i class="fas fa-box-open fa-2x text-danger mb-2"></i>
        <h4><?= $lang['feature_resource_coordination_title'] ?></h4>
        <p><?= $lang['feature_resource_coordination_desc'] ?></p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white pt-5">
    <!-- Disaster Management Info Cards -->
    <div class="container py-5" style="background-color: #1c1c1c;">
      <div class="row g-4 text-white">

        <!-- Resource Collection Card -->
        <div class="col-md-4">
          <div class="card bg-dark text-white h-100 shadow-lg border-0 rounded-4">
            <div class="card-body text-center">
              <div class="fs-1 mb-3">📦</div>
              <h5 class="card-title fw-bold"><?= $lang['footer_resource_collection_title'] ?></h5>
              <p class="card-text"><?= $lang['footer_resource_collection_desc'] ?></p>
            </div>
          </div>
        </div>

        <!-- Volunteer Card -->
        <div class="col-md-4">
          <div class="card bg-dark text-white h-100 shadow-lg border-0 rounded-4">
            <div class="card-body text-center">
              <div class="fs-1 mb-3">🙋‍♂️</div>
              <h5 class="card-title fw-bold"><?= $lang['footer_volunteer_title'] ?></h5>
              <p class="card-text"><?= $lang['footer_volunteer_desc'] ?></p>
            </div>
          </div>
        </div>

        <!-- Report Incident Card -->
        <div class="col-md-4">
          <div class="card bg-dark text-white h-100 shadow-lg border-0 rounded-4">
            <div class="card-body text-center">
              <div class="fs-1 mb-3">📣</div>
              <h5 class="card-title fw-bold"><?= $lang['footer_report_incident_title'] ?></h5>
              <p class="card-text"><?= $lang['footer_report_incident_desc'] ?></p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-top border-light pt-4 mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="mb-2 mb-md-0">
        <span class="fw-bold"><?= $lang['relief_sphere'] ?></span> &copy; 2025
      </div>
      <div class="mb-2 mb-md-0 d-flex gap-3">
        <a href="https://twitter.com" class="text-white"><i class="fab fa-twitter"></i></a>
        <a href="https://facebook.com" class="text-white"><i class="fab fa-facebook-f"></i></a>
        <a href="https://instagram.com" class="text-white"><i class="fab fa-instagram"></i></a>
        <a href="https://plus.google.com" class="text-white"><i class="fab fa-google"></i></a>
        <a href="https://linkedin.com" class="text-white"><i class="fab fa-linkedin"></i></a>
      </div>
      <div class="footer-bottom">
        <span>
          <a href="contact.php"><?= $lang['contact'] ?></a>
          <a href="https://reliefweb.int/"><?= $lang['blog'] ?></a>
        </span>
      </div>
    </div>
  </footer>
</body>
</html>
