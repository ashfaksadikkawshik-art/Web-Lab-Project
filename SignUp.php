<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack Pro - Sign Up</title>

  <link rel="stylesheet" href="SignUp.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body>

<?php

$conn = mysqli_connect("localhost","root","","fittrack");

if(!$conn){
    die("Database connection failed");
}

$message = "";

if(isset($_POST['fullname']) &&
   isset($_POST['email']) &&
   isset($_POST['password'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users(fullname,email,password)
            VALUES('$fullname','$email','$password')";

    if(mysqli_query($conn,$sql)){

        header("Location: index.php");
        exit();

    }
    else{
        $message = "Account creation failed";
    }
}

?>

<div class="container">

    <!-- Logo -->
    <div class="logo">
      <div class="icon">
        <i class="fa-solid fa-dumbbell"></i>
      </div>

      <h1>FitTrack Pro</h1>

      <p>Start your fitness journey today</p>
    </div>

    <!-- Card -->
    <div class="card">

      <form method="POST">

        <!-- Name -->
        <div class="input-group">
          <label>Full Name</label>

          <div class="input-box">
            <i class="fa-regular fa-user"></i>

            <input type="text"
            name="fullname"
            placeholder="John Doe"
            required>
          </div>
        </div>

        <!-- Email -->
        <div class="input-group">
          <label>Email Address</label>

          <div class="input-box">
            <i class="fa-regular fa-envelope"></i>

            <input type="email"
            name="email"
            placeholder="john@example.com"
            required>
          </div>
        </div>

        <!-- Password -->
        <div class="input-group">
          <label>Password</label>

          <div class="input-box">
            <i class="fa-solid fa-lock"></i>

            <input type="password"
            name="password"
            placeholder="••••••••"
            required>
          </div>
        </div>

        <!-- Button -->
        <button type="submit" class="btn">
          Create Account
          <i class="fa-solid fa-arrow-right"></i>
        </button>

      </form>

      <!-- Message -->
      <?php
      if($message != ""){
          echo "<div class='message'>$message</div>";
      }
      ?>

      <!-- Login -->
      <p class="login-text">
        Already have an account?
        <a href="Login.php">Sign in</a>
      </p>

    </div>

</div>

</body>
</html>