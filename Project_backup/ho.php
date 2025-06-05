<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: logins.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Disaster Relief Platform</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --bg: #ffffff;
      --text: #000000;
      --card-bg: #ffffff;
      --footer-bg: #111111;
      --footer-text: #ffffff;
    }

    [data-theme="dark"] {
      --bg: #1a1a1a;
      --text: #f0f0f0;
      --card-bg: #2a2a2a;
      --footer-bg: #000000;
      --footer-text: #ffffff;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      transition: background-color 0.3s, color 0.3s;
    }
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
      
    }

    .navbar {
      height: 70px;
      font-size: 19px;
    }
    .navbar-dark .navbar-nav .nav-link {
  color: #ffffff !important;
}
.navbar-nav .nav-link {
      font-weight: 600;
      
    }

     .navbar-brand {
      color: white;
      font-weight: bold;
    }

     .nav-link:hover {
      color: #00bfff !important;
    }

    .hero {
      background-size: cover;
      background-position: center;
      height: 90vh;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }

    .hero-content {
      position: relative;
      z-index: 1;
      animation: slideIn 1s ease-in-out;
    }

    @keyframes slideIn {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .disaster-info {
      padding: 40px 20px;
      background-color: #f0f4f7;
      text-align: center;
    }

    [data-theme="dark"] .disaster-info {
      background-color: #222;
    }

    .disaster-info h2 {
      color: #004080;
      margin-bottom: 30px;
      font-size: 28px;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }

    .card {
      background: var(--card-bg);
      border-radius: 10px;
      padding: 20px;
      width: 300px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .card h3 {
      color: #004080;
      margin-bottom: 10px;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .custom-footer {
      background-color: var(--footer-bg);
      color: var(--footer-text);
      text-align: center;
      padding: 40px 20px 20px;
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
      color: var(--footer-text);
      margin-left: 10px;
      text-decoration: none;
    }

    .social-icons a {
      display: inline-block;
      margin: 0 10px;
      color: #fff;
      background: #222;
      padding: 10px;
      border-radius: 50%;
      transition: background 0.3s;
    }

    .social-icons a:hover {
      background: #007bff;
    }

    .theme-switch {
      display: flex;
      align-items: center;
      gap: 10px;
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
  <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
    <img src="logo.jpeg" alt="Logo" width="40" height="35" class="rounded-circle">
    Relief Sphere
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto align-items-lg-center">
      <li class="nav-item"><a class="nav-link" href="index.php">About Us</a></li>
      <li class="nav-item"><a class="nav-link" href="report.php">Report Incident</a></li>
      <li class="nav-item"><a class="nav-link" href="volunteer_form.php">Volunteer</a></li>
      <li class="nav-item"><a class="nav-link" href="resource.php">Resource Collection</a></li>
      <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-info" href="#" role="button" data-bs-toggle="dropdown">
          <i class="fa fa-user-circle me-1"></i><?php echo $_SESSION['user_name'] ?? 'Profile'; ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profile.php"><i class="fa fa-user me-2"></i>Profile</a></li>
          <li><a class="dropdown-item" href="change_password.php"><i class="fa fa-lock me-2"></i>Change Password</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </li>

      <li class="nav-item theme-switch">
        <button class="btn btn-outline-light btn-sm" id="themeToggle"><i class="fa fa-moon"></i></button>
      </li>
    </ul>
  </div>
</nav>


  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-content">
      <h1 class="display-4">Coordinating Disaster Relief Efficiently</h1>
      <p class="lead">Real-time data. Smarter help. Stronger communities.</p>
    </div>
  </section>

  <!-- Disaster Awareness Sections -->
  <section class="disaster-info">
    <h2>Disaster Awareness</h2>
    <div class="card-container">
      <div class="card"><h3>🌪 Cyclone</h3><p>High-speed winds and heavy rains can cause flooding and destruction. Stay alert and prepare evacuation plans.</p></div>
      <div class="card"><h3>🔥 Wildfire</h3><p>Uncontrolled fires in forests or grasslands. Stay alert for evacuation notices and avoid smoke exposure.</p></div>
      <div class="card"><h3>🌊 Flood</h3><p>Rising waters from rain or overflow. Know safe routes, avoid water-logged areas, and stay informed.</p></div>
    </div>
  </section>

  <section class="disaster-info">
    <div class="card-container">
      <div class="card"><h3>⛑ Earthquake Response</h3><p>Prepare before, during, and after. Identify safe zones, secure heavy objects, and expect aftershocks.</p></div>
      <div class="card"><h3>💧 Drought Relief</h3><p>Droughts cause water scarcity and crop failure. Learn conservation strategies and emergency water supply programs.</p></div>
      <div class="card"><h3>🦠 Pandemic Preparedness</h3><p>Quick health response needed. Ensure hygiene, medical supplies, and monitor health advisories.</p></div>
    </div>
  </section>

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
      <span>&copy; 2025 DisasterRelief</span>
      <span>
        <a href="index.php">Home</a>
        <a href="contact.php">Contact</a>
        <a href="https://reliefweb.int/" target="_blank">Blog</a>
      </span>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    const hero = document.getElementById("hero");
    const images = [
      'https://images.pexels.com/photos/942560/pexels-photo-942560.jpeg',
      'https://media.istockphoto.com/id/1266552048/photo/forest-fire-wildfire-at-night-time-on-the-mountain-with-big-smoke.jpg?s=612x612&w=0&k=20&c=A_wU9V1aYwWR2WQgJgN9I4lYpYkHr7J8IFBhrSa36tY=',
      'https://media.istockphoto.com/id/145721138/video/cyclone-storm-surge.jpg?s=640x640&k=20&c=K-PrCJhOEE2fN7w-BQh16bTWvOGWIi5MsDM2KI4xWVA=',
      'https://media.istockphoto.com/id/619382694/photo/earthquake-tsunami-japan-311.jpg?s=612x612&w=0&k=20&c=DdA_XTRq8z_a5UXwvRgh1daKYCwZrcY1Rjnv5sTW3Bc=',
      'https://media.istockphoto.com/id/1273568227/photo/fire-fighting-helicopter-carry-water-bucket-to-extinguish-the-forest-fire.jpg?s=612x612&w=0&k=20&c=asdQcPQ2up1JNqk9iHvzwguIOOpU9OG4DmFYKfAAjvw='
    ];
    let current = 0;
    function changeBackground() {
      current = (current + 1) % images.length;
      hero.style.backgroundImage = `url('${images[current]}')`;
    }
    setInterval(changeBackground, 4000);

    // Theme Switcher
    const themeToggle = document.getElementById("themeToggle");
    const html = document.documentElement;

    function applyTheme(theme) {
      html.setAttribute("data-theme", theme);
      localStorage.setItem("theme", theme);
      themeToggle.innerHTML = theme === "dark" ? '<i class="fa fa-sun"></i>' : '<i class="fa fa-moon"></i>';
    }

    themeToggle.addEventListener("click", () => {
      const currentTheme = html.getAttribute("data-theme");
      applyTheme(currentTheme === "dark" ? "light" : "dark");
    });

    applyTheme(localStorage.getItem("theme") || "light");
  </script>
</body>
</html>
