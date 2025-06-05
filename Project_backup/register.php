<?php
ob_start();
session_start();
include 'db.php';
include 'language_loader.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];
    $phone    = $_POST["phone"];

    if ($email === 'admin@gmail.com') {
        $message = "<div class='alert alert-danger'>❌ " . $lang['admin_email_restricted'] . "</div>";
    } elseif (empty($name) || empty($email) || empty($password) || empty($confirm) || empty($phone)) {
        $message = "<div class='alert alert-danger'>" . $lang['all_fields_required'] . "</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>" . $lang['invalid_email'] . "</div>";
    } elseif ($password !== $confirm) {
        $message = "<div class='alert alert-danger'>" . $lang['passwords_not_match'] . "</div>";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "<div class='alert alert-danger'>" . $lang['email_exists'] . "</div>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->bind_param("ssss", $name, $email, $hashed, $phone);

            if ($stmt->execute()) {
                header("Location: logins.php?msg=" . urlencode($lang['account_created']));
                exit();
            } else {
                $message = "<div class='alert alert-danger'>" . $lang['error_try_again'] . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? 'en'); ?>">
<head>
  <meta charset="UTF-8">
  <title><?php echo $lang['create_account']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
  <style>
    .form-container {
      background: rgba(255, 255, 255, 0.2);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.3);
      max-width: 400px;
      width: 100%;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    body {
      background: linear-gradient(to right, rgb(255, 255, 255), rgb(16, 150, 173));
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container h2 {
      margin-bottom: 20px;
    }

    .form-control:focus {
      box-shadow: 0 0 10px rgba(41, 128, 185, 0.5);
    }

    .iti {
      width: 100%;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2 class="text-center"><i class="fas fa-user-plus"></i> <?php echo $lang['create_account']; ?></h2>
    <?php echo $message; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label"><i class="fas fa-user"></i> <?php echo $lang['full_name']; ?></label>
        <input type="text" class="form-control" name="name" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fas fa-envelope"></i> <?php echo $lang['email']; ?></label>
        <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fas fa-lock"></i> <?php echo $lang['password']; ?></label>
        <input type="password" class="form-control" name="password" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fas fa-lock"></i> <?php echo $lang['confirm_password']; ?></label>
        <input type="password" class="form-control" name="confirm" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="fas fa-phone"></i> <?php echo $lang['phone']; ?></label>
        <input type="tel" class="form-control" name="phone" id="phone" required value="<?php echo htmlspecialchars($phone ?? ''); ?>">
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-user-plus"></i> <?php echo $lang['register']; ?>
      </button>
    </form>

    <div class="mt-3 text-center">
      <p><?php echo $lang['already_have_account']; ?> <a href="logins.php"><?php echo $lang['login_here']; ?></a></p>
    </div>
  </div>

  <!-- JS & IntlTelInput -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <script>
    const input = document.querySelector("#phone");
    const iti = window.intlTelInput(input, {
      initialCountry: "us",
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
  </script>

</body>
</html>
<?php ob_end_flush(); ?>
