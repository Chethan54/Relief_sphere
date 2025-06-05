<?php
include 'db.php';
session_start();

$error = '';
$success = '';

// Check if the session has the verification code and phone
if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_phone'])) {
    header("Location: forgot_password.php");
    exit();
}

$session_code = $_SESSION['reset_code'];
$phone = $_SESSION['reset_phone'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = trim($_POST['code']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($entered_code != $session_code) {
        $error = "❌ Incorrect verification code.";
    } elseif (strlen($new_password) < 6) {
        $error = "❌ Password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "❌ Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update in database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE phone = ?");
        $stmt->bind_param("ss", $hashed_password, $phone);

        if ($stmt->execute()) {
            $success = "✅ Password reset successfully! You can now log in.";
            // Clear session
            unset($_SESSION['reset_code']);
            unset($_SESSION['reset_phone']);
        } else {
            $error = "❌ Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Disaster Relief</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(120deg, #f3f4f6, #e0f7fa);
        }
        .card {
            border: none;
            border-radius: 12px;
        }
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .form-control {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <div class="card shadow p-4">
            <h4 class="text-center mb-3"><i class="fas fa-lock"></i> Reset Your Password</h4>

            <!-- Alerts -->
            <?php if (!empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3 position-relative">
                    <i class="fas fa-key form-icon"></i>
                    <input type="text" name="code" class="form-control" placeholder="Enter 6-digit verification code" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="fas fa-lock form-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="New password" required>
                </div>
                <div class="mb-3 position-relative">
                    <i class="fas fa-lock form-icon"></i>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>

            <?php if (!empty($success)) : ?>
                <div class="text-center mt-3">
                    <a href="logins.php" class="btn btn-success w-100">Go to Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
