<?php
include 'language_loader.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_donation'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $amount = $_POST['amount'];
    $message = $_POST['message'];

    $sql = "INSERT INTO donations (name, email, amount, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $email, $amount, $message);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('{$lang['donation_thank_you']}'); window.location.href='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($lang['donate_funds']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .payment-option {
      cursor: pointer;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 12px;
      text-align: center;
      transition: 0.3s ease;
    }
    .payment-option:hover, .payment-option.active {
      border-color: #0d6efd;
      background-color: #f0f8ff;
    }
    #qr-code {
      max-width: 220px;
      margin: 20px auto;
      display: none;
    }
    .card-form, .paypal-button-container, .netbanking-section {
      display: none;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
  <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
    <img src="logo.jpeg" alt="Logo" width="40" height="35" class="rounded-circle">
    <?= $lang['site_title'] ?? 'Relief Sphere' ?>
  </a>
</nav>

<div class="container mt-5">
  <h1 class="text-center"><?= htmlspecialchars($lang['donate_funds']) ?></h1>
  <div class="donate-form shadow bg-white p-4 rounded mt-4">
    <form method="POST" id="donation-form" onsubmit="return validateForm()">
      <div class="mb-3">
        <label class="form-label"><?= $lang['full_name'] ?> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= $lang['email_address'] ?> <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= $lang['donation_amount'] ?> <span class="text-danger">*</span></label>
        <input type="number" name="amount" class="form-control" required min="1" step="0.01">
      </div>
      <div class="mb-3">
        <label class="form-label"><?= $lang['message_optional'] ?></label>
        <textarea name="message" class="form-control" rows="3"></textarea>
      </div>

      <h5 class="mt-4"><?= $lang['select_payment_method'] ?? 'Select Payment Method' ?></h5>
      <div class="row g-3 mb-3">
        <div class="col-3 payment-option" onclick="selectPayment('upi')">
          <img src="scanner.png" height="30" alt="UPI">
          <div>Scanner</div>
        </div>
        <div class="col-3 payment-option" onclick="selectPayment('card')">
          <i class="bi bi-credit-card fs-4"></i>
          <div>Card</div>
        </div>
        <div class="col-3 payment-option" onclick="selectPayment('paypal')">
          <img src="paypal.png" height="30" alt="PayPal">
          <div>PayPal</div>
        </div>
        <div class="col-3 payment-option" onclick="selectPayment('netbanking')">
          <i class="bi bi-bank2 fs-4"></i>
          <div>Net Banking</div>
        </div>
      </div>

      <!-- UPI QR -->
      <div class="text-center">
        <img src="scanner.png" id="qr-code" alt="QR Code">
        <p class="text-muted mt-2 d-none" id="qr-note"><?= $lang['scan_qr_note'] ?? 'Scan the QR code using your UPI app to pay' ?></p>
      </div>

      <!-- Card Payment -->
      <div id="card-section" class="card-form">
        <div class="mb-3">
          <label class="form-label">Card Number</label>
          <input type="text" id="cardNumber" class="form-control" maxlength="16" pattern="\d{16}">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Expiry (MM/YY)</label>
            <input type="text" id="expiry" class="form-control" placeholder="MM/YY" maxlength="5">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">CVV</label>
            <input type="text" id="cvv" class="form-control" maxlength="3" pattern="\d{3}">
          </div>
        </div>
      </div>

      <!-- PayPal Button -->
      <div id="paypal-section" class="paypal-button-container text-center mt-3">
        <div id="paypal-button-container"></div>
      </div>

      <!-- Net Banking -->
      <div id="netbanking-section" class="netbanking-section text-center mt-3">
        <p><strong>Select App:</strong></p>
        <a href="https://pay.google.com" target="_blank" class="btn btn-outline-primary mx-2">Google Pay</a>
        <a href="https://www.phonepe.com" target="_blank" class="btn btn-outline-secondary mx-2">PhonePe</a>
      </div>

      <div class="text-center mt-4">
        <button type="submit" name="submit_donation" class="btn btn-success btn-lg">
          <i class="bi bi-heart-fill"></i> <?= $lang['donate_now'] ?? 'Donate Now' ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_CLIENT_ID_HERE&currency=USD"></script>
<script>
paypal.Buttons({
  createOrder: function(data, actions) {
    const amount = document.querySelector('input[name="amount"]').value || "1.00";
    return actions.order.create({
      purchase_units: [{ amount: { value: amount } }]
    });
  },
  onApprove: function(data, actions) {
    return actions.order.capture().then(function(details) {
      alert('Payment completed by ' + details.payer.name.given_name);
      document.getElementById('donation-form').submit();
    });
  }
}).render('#paypal-button-container');

function selectPayment(method) {
  document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.card-form, .paypal-button-container, #netbanking-section').forEach(el => el.style.display = 'none');
  document.getElementById('qr-code').style.display = 'none';
  document.getElementById('qr-note').classList.add('d-none');

  if (method === 'upi') {
    document.getElementById('qr-code').style.display = 'block';
    document.getElementById('qr-note').classList.remove('d-none');
  }
  if (method === 'card') document.getElementById('card-section').style.display = 'block';
  if (method === 'paypal') document.getElementById('paypal-section').style.display = 'block';
  if (method === 'netbanking') document.getElementById('netbanking-section').style.display = 'block';

  document.querySelectorAll('.payment-option').forEach(option => {
    if (option.innerText.toLowerCase().includes(method)) option.classList.add('active');
  });
}

function validateForm() {
  const email = document.querySelector('input[name="email"]').value;
  const amount = parseFloat(document.querySelector('input[name="amount"]').value);
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailRegex.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }
  if (isNaN(amount) || amount <= 0) {
    alert("Please enter a valid donation amount.");
    return false;
  }

  const cardSection = document.getElementById('card-section');
  if (cardSection.style.display === 'block') {
    const cardNumber = document.getElementById('cardNumber').value;
    const expiry = document.getElementById('expiry').value;
    const cvv = document.getElementById('cvv').value;

    if (!/^\d{16}$/.test(cardNumber)) {
      alert("Please enter a valid 16-digit card number.");
      return false;
    }
    if (!/^\d{2}\/\d{2}$/.test(expiry)) {
      alert("Please enter expiry in MM/YY format.");
      return false;
    }
    if (!/^\d{3}$/.test(cvv)) {
      alert("Please enter a valid 3-digit CVV.");
      return false;
    }
    alert("Simulated card payment successful.");
  }

  return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
