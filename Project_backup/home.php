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
  <title><?= $lang['platform_title'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    nav {
      position: relative;
      z-index: 1;
      height: 70px;
      font-size:20px;
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
    .theme-switch {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .card-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    .card {
      background: white;
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
    .card p {
      font-size: 14px;
      color: #444;
    }
    .card:hover {
      transform: translateY(-5px);
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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <<a class="navbar-brand d-flex align-items-center gap-2">
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
      <li class="nav-item"><a class="nav-link active" href="volunteer_form.php"><?= $lang['nav_volunteer'] ?></a></li>
      <li class="nav-item"><a class="nav-link active" href="resource.php"><?= $lang['nav_resource'] ?></a></li>
      <li class="nav-item"><a class="nav-link active" href="contact.php"><?= $lang['nav_contact'] ?></a></li>
      <li class="nav-item theme-switch">
        <button class="btn btn-outline-light btn-sm" id="themeToggle"><i class="fa fa-moon"></i></button>
      </li>
    </ul>
  </div>
</nav>

<section class="hero" id="hero">
  <div class="hero-content">
    <h1 class="display-4"><?= $lang['hero_title'] ?></h1>
    <p class="lead"><?= $lang['hero_subtitle'] ?></p>
  </div>
</section>

<section class="disaster-info">
  <h2><?= $lang['disaster_awareness'] ?></h2>
  <div class="card-container">
    <div class="card">
      <h3>🌪 <?= $lang['cyclone'] ?></h3>
      <p><?= $lang['cyclone_info'] ?></p>
    </div>
    <div class="card">
      <h3>🔥 <?= $lang['wildfire'] ?></h3>
      <p><?= $lang['wildfire_info'] ?></p>
    </div>
    <div class="card">
      <h3>🌊 <?= $lang['flood'] ?></h3>
      <p><?= $lang['flood_info'] ?></p>
    </div>
  </div>
</section>

<section class="disaster-info">
  <div class="card-container">
    <div class="card">
      <h3>⛑ <?= $lang['earthquake'] ?></h3>
      <p><?= $lang['earthquake_info'] ?></p>
    </div>
    <div class="card">
      <h3>💧 <?= $lang['drought'] ?></h3>
      <p><?= $lang['drought_info'] ?></p>
    </div>
    <div class="card">
      <h3>🦠 <?= $lang['pandemic'] ?></h3>
      <p><?= $lang['pandemic_info'] ?></p>
    </div>
  </div>
</section>

<footer class="custom-footer">
  <div class="social-icons">
    <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
    <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
    <a href="https://plus.google.com" target="_blank"><i class="fab fa-google"></i></a>
    <a href="https://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
    <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
  </div>
  <div class="footer-bottom">
    <span><?= $lang['footer_rights'] ?></span>
    <span>
      <a href="index.php"><?= $lang['nav_home'] ?></a>
      <a href="contact.php"><?= $lang['nav_contact'] ?></a>
      <a href="https://reliefweb.int/"><?= $lang['footer_blog'] ?></a>
    </span>
  </div>
</footer>

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
  changeBackground();
  setInterval(changeBackground, 4000);

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
