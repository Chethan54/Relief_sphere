<?php
include 'db.php';
include 'language_loader.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

// Change language via GET
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login    = trim($_POST['login']);
    $password = $_POST['password'];

    if ($login === 'admin@gmail.com' && $password === 'admin@123') {
        $_SESSION['user_email'] = $login;
        $_SESSION['username'] = "Admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, name, email, password, phone FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $email, $hashed_password, $phone);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id']    = $id;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['username']   = $name;
            header("Location: index.php");
            exit();
        } else {
            $error = $lang['invalid_password'];
        }
    } else {
        $error = $lang['email_not_found'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['login_title'] ?> - Relief Sphere</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

    <style>
        body {
            margin: 0;
            background: url('https://images.pexels.com/photos/16105713/pexels-photo-16105713/free-photo-of-group-of-paramedics-walking-through-a-demolished-city.jpeg?auto=compress&cs=tinysrgb&w=600') no-repeat center center/cover;
            height: 100vh;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 56px);
        }

        .login-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-icon {
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
        }

        .btn-outline-primary {
            transition: all 0.3s ease;
            border-radius: 30px;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn:hover {
            transform: scale(0.95);
        }

        .navbar {
            background-color: black;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .alert-validation {
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: auto;
        }

        .iti {
            width: 100%;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <<a class="navbar-brand d-flex align-items-center gap-2">
  <img src="logo.jpeg" alt="Logo" width="40" height="35" class="d-inline-block align-text-top rounded-circle">
  <?= $lang['site_title'] ?? 'Relief Sphere' ?>
</a>
    <div class="ms-auto">
        <form method="get" class="d-flex align-items-center">
            <select name="lang" onchange="this.form.submit()" class="form-select form-select-sm text-bg-light">
                <option value="en" <?= ($_SESSION['lang'] ?? 'en') == 'en' ? 'selected' : '' ?>>English</option>
                <option value="hi" <?= ($_SESSION['lang'] ?? '') == 'hi' ? 'selected' : '' ?>>हिन्दी</option>
                <option value="ta" <?= ($_SESSION['lang'] ?? '') == 'ta' ? 'selected' : '' ?>>தமிழ்</option>
                <option value="kn" <?= ($_SESSION['lang'] ?? '') == 'kn' ? 'selected' : '' ?>>ಕನ್ನಡ</option>
                <option value="te" <?= ($_SESSION['lang'] ?? '') == 'te' ? 'selected' : '' ?>>తెలుగు</option>
                <option value="ml" <?= ($_SESSION['lang'] ?? '') == 'ml' ? 'selected' : '' ?>>മലയാളം</option>
            </select>
        </form>
    </div>
</nav>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show alert-validation" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="login-container">
    <div class="login-box">
        <div class="text-center">
            <i class="fas fa-user-shield form-icon"></i>
            <h4 class="mb-3"><?= $lang['login_title'] ?></h4>
        </div>

        <form method="POST" id="loginForm">
            <div class="mb-3">
                <label for="login" class="form-label"><?= $lang['email_or_phone'] ?></label>
                <input required type="text" name="login" class="form-control" id="login" placeholder="<?= $lang['email_or_phone'] ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><?= $lang['password'] ?></label>
                <input required type="password" name="password" class="form-control" id="password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary w-100"><?= $lang['login_btn'] ?> <i class="fas fa-sign-in-alt ms-2"></i></button>
        </form>

        <div class="text-center mt-4">
            <p><?= $lang['no_account'] ?>
                <a href="register.php" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-user-plus"></i> <?= $lang['create_account'] ?>
                </a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<script>
    const input = document.querySelector("#login");
    const iti = window.intlTelInput(input, {
        initialCountry: "in",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });

    document.getElementById("loginForm").addEventListener("submit", function (e) {
        const loginInput = document.getElementById("login").value.trim();
        const isEmail = loginInput.includes('@');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneNumberValid = iti.isValidNumber();

        if (isEmail && !emailRegex.test(loginInput)) {
            e.preventDefault();
            showError("<?= $lang['invalid_email'] ?>");
        } else if (!isEmail && !phoneNumberValid) {
            e.preventDefault();
            showError("<?= $lang['invalid_phone'] ?>");
        }
    });

    function showError(message) {
        const alert = document.createElement("div");
        alert.className = "alert alert-warning alert-dismissible fade show alert-validation";
        alert.role = "alert";
        alert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);
    }
</script>


</body>
</html>
