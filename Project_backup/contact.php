<?php
include 'db.php';
include 'language_loader.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first  = trim($_POST["first_name"]);
    $last   = trim($_POST["last_name"]);
    $email  = trim($_POST["email"]);
    $phone  = trim($_POST["phone"]);
    $query  = trim($_POST["message"]);

    if (empty($first) || empty($last) || empty($email) || empty($query)) {
        $message = "<div class='alert alert-danger'>{$lang['fill_required_fields']}</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>{$lang['invalid_email']}</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (first_name, last_name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first, $last, $email, $phone, $query);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>{$lang['thank_you']} $first! {$lang['response_soon']}</div>";
        } else {
            $message = "<div class='alert alert-danger'>{$lang['submission_error']}</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $lang['contact_us'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      background: url('https://images.timesproperty.com/blog/8523/Disaster_Relief.jpg') no-repeat center center/cover;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 80px;
    }
    .top-bar {
      position: absolute;
      top: 0;
      width: 100%;
      height: 70px;
      font-size: 20px;
      background-color: rgb(49, 49, 49);
      color: white;
      padding: 10px 20px;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .top-bar a {
      color: white;
      text-decoration: none;
      font-size: 1.3rem;
    }
    .contact-wrapper {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      padding: 60px 20px;
    }
    .contact-form {
      background: rgba(255, 255, 255, 0.2);
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 600px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .form-label i {
      margin-right: 8px;
      color: #2980b9;
    }
    .form-control {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      background: rgba(255, 255, 255, 0.2);
    }
  </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar d-flex align-items-center justify-content-between">
  <div>
    <a href="index.php"><i class="fas fa-home"></i> <?= $lang['home'] ?></a>
  </div>
  <div>
    <strong><?= $lang['contact_us'] ?></strong>
  </div>
</div>

<div class="container contact-wrapper">
  <div class="contact-form">
    <h2><i class="fas fa-envelope-open-text"></i> <?= $lang['contact_us'] ?></h2>
    <?= $message ?>

    <form method="POST" action="">
      <div class="row mb-3">
        <div class="col">
          <label class="form-label"><i class="fas fa-user"></i> <?= $lang['first_name'] ?></label>
          <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="col">
          <label class="form-label"><i class="fas fa-user"></i> <?= $lang['last_name'] ?></label>
          <input type="text" name="last_name" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col">
          <label class="form-label"><i class="fas fa-envelope"></i> <?= $lang['email'] ?></label>
          <input 
            type="email" 
            name="email" 
            class="form-control" 
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
            title="<?= $lang['valid_email'] ?>" 
            required
          >
        </div>

        <div class="col">
          <label class="form-label"><i class="fas fa-phone"></i> <?= $lang['phone'] ?></label>
          <input 
            type="tel" 
            name="phone" 
            class="form-control" 
            pattern="[0-9]{10}" 
            maxlength="10"
            title="<?= $lang['valid_phone'] ?>"
            required
          >
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fas fa-comment-dots"></i> <?= $lang['message'] ?></label>
        <textarea name="message" rows="4" class="form-control" placeholder="<?= $lang['message_placeholder'] ?>" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-paper-plane"></i> <?= $lang['submit'] ?>
      </button>
    </form>
  </div>
</div>

</body>
</html>
