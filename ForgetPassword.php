<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FitTrack Pro - Reset Password</title>

  <link rel="stylesheet" href="ForgetPassword.css" />

  <style>
    .msg{
      margin-top:15px;
      color:green;
      font-size:14px;
    }
  </style>
</head>
<body>

<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];

    if (!empty($email)) {

        $token = bin2hex(random_bytes(16));

        $resetLink = "http://localhost/reset.php?token=" . $token;

        $subject = "Password Reset Link";
        $body = "Click here to reset your password: " . $resetLink;

        // mail($email, $subject, $body);

        $message = "Reset link generated (demo): " . $resetLink;

    } else {
        $message = "Please enter your email!";
    }
}
?>

  <div class="container">

    <div class="logo">
      <div class="icon">💪</div>
      <h1>FitTrack Pro</h1>
      <p>Reset your password</p>
    </div>

    <div class="card">

      <form method="POST">
        <label>Email Address</label>

        <div class="input-box">
          <span class="icon">✉️</span>
          <input type="email" name="email" placeholder="john@example.com" required />
        </div>

        <button class="btn" type="submit">
          Send Reset Link →
        </button>
      </form>

      <p class="bottom-text">
        Remember your password? <a href="login.php">Sign in</a>
      </p>

      <?php if ($message != "") { ?>
        <div class="msg">
          <?php echo $message; ?>
        </div>
      <?php } ?>

    </div>

  </div>

</body>
</html>