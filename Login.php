<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack Pro Login</title>

  <!-- CSS -->
  <link rel="stylesheet" href="Login.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="container">

  <!-- Logo -->
    <div class="logo">
      <div class="icon">
        <i class="fa-solid fa-dumbbell"></i>
      </div>
    </div>

  <!-- Heading -->
  <h1>FitTrack Pro</h1>

  <p class="subtitle">
    Welcome back! Ready to crush your fitness goals?
  </p>

  <!-- Card -->
  <div class="card">

    <form method="POST">

      <!-- Email -->
      <label class="label">Email Address</label>

      <div class="input-box">
        <i class="fa-regular fa-envelope"></i>

        <input type="email"
        name="email"
        placeholder="john@example.com"
        required>
      </div>

      <!-- Password -->
      <label class="label">Password</label>

      <div class="input-box">
        <i class="fa-solid fa-lock"></i>

        <input type="password"
        name="password"
        placeholder="••••••••"
        required>
      </div>

      <!-- Forgot Password -->
      <div class="forgot">
        <a href="ForgetPassword.php">Forgot Password?</a>
      </div>

      <!-- Login Button -->
      <button type="submit" class="btn">
        Log In
        <i class="fa-solid fa-arrow-right"></i>
      </button>

    </form>

<!-- Signup -->
    <div class="signup">
      Don't have an account?
      <a href="SignUp.php">Sign up</a>
    </div>

<?php

$conn = mysqli_connect("localhost","root","","fittrack");

if(!$conn){
    die("<div class='message error'>Database connection failed</div>");
}

if(isset($_POST['email']) && isset($_POST['password'])){

   $email = $_POST['email'];
   $password = $_POST['password'];

   $sql = "SELECT * FROM users
           WHERE email='$email'
           AND password='$password'";

   $result = mysqli_query($conn,$sql);

   if(mysqli_num_rows($result) > 0){
      echo "<div class='message'>Login Successful</div>";
   }
   else{
      echo "<div class='message error'>Wrong Email or Password</div>";
   }
}

?>

  </div>
</div>

</body>
</html>