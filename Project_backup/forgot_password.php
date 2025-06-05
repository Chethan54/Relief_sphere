<?php
include 'db.php';
session_start();

$error = '';
$success = '';

// Msg91 API Key and Sender ID (replace with your actual values)
$apiKey = '451336AnsCVUpbeR68232025P1';  // Replace with your Msg91 API Key
$senderID = 'DisasterRelief';  // Replace with your Msg91 Sender ID
$route = '4';  // Route for transactional SMS (use 1 for promotional SMS)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);

    // Basic phone number validation (10-15 digits)
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "❌ Invalid phone number format.";
    } else {
        // Check if phone exists in the users table
        $stmt = $conn->prepare("SELECT id, name, phone FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $phone);
            $stmt->fetch();

            // Generate a 6-digit verification code
            $code = rand(100000, 999999);

            // Store code and phone in session
            $_SESSION['reset_code'] = $code;
            $_SESSION['reset_phone'] = $phone;

            // Prepare Msg91 API request
            $message = "Your DisasterRelief verification code is: $code";
            $data = [
                'authkey' => $apiKey,
                'mobiles' => $phone,
                'message' => $message,
                'sender' => $senderID,
                'route' => $route,
                'country' => '91'  // Country code for India (adjust accordingly)
            ];

            // Initialize cURL session
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.msg91.com/api/sendhttp.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ]);

            // Execute the request and capture the response
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                $error = "❌ SMS sending failed: " . curl_error($ch);
            } else {
                // Log the response for debugging if needed
                // echo $response; 

                // Redirect to the password reset page
                header("Location: reset_password.php");
                exit();
            }
        } else {
            $error = "❌ Phone number not found in our records.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Disaster Relief</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</head>
<body>

<!-- Display error if any -->
<?php if (!empty($error)) : ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Forgot Password Form -->
<div class="container mt-5">
  <div class="col-md-6 offset-md-3">
    <div class="card shadow p-4">
      <h4 class="text-center mb-3">Forgot Password</h4>
      <form method="POST">
        <div class="mb-3">
          <label for="phone" class="form-label">Registered Phone Number</label>
          <input required type="text" name="phone" class="form-control" id="phone" placeholder="Enter your phone number">
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Verification Code</button>
      </form>
      <div class="text-center mt-3">
        <a href="logins.php" class="btn btn-link">Back to Login</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
