<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$loggedInUser = $_SESSION['user'];
$user = ['name' => strtoupper(substr($loggedInUser, 0, 1)), 'streak' => 12];

$exerciseDays = "";
$restDays = "";
$showStatus = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exerciseDays = htmlspecialchars($_POST['exercise_days']);
    $restDays = htmlspecialchars($_POST['rest_days']);
    $showStatus = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack Pro – Fitness Tracking</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
  <link rel="stylesheet" href="streak.css">
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="sidebar-logo-inner">
        <div class="logo-icon">⚡</div>
        <div>
          <div class="logo-name">FitTrack Pro</div>
          <div class="logo-sub">Your Fitness Journey</div>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="dashboard.php" class="nav-item">Dashboard</a>
      <a href="goal.php" class="nav-item">Goals</a>
      <a href="workouts.php" class="nav-item">Workouts</a>
      <a href="analytics.php" class="nav-item">Analytics</a>
      <a href="profile.php" class="nav-item">Profile</a>
      <a href="streak.php" class="nav-item active">Streak</a>
    </nav>

    <div class="motiv-card">
      <div class="title">💪 Keep Going!</div>
      <div class="sub">You're on a <?= $user['streak'] ?>-day streak.<br>Don't break it now!</div>
    </div>
  </aside>

  <main class="main">
    <header class="page-header">
      <div>
        <h1 class="page-title">Fitness Tracking Routine 👟</h1>
        <p class="page-sub">Manage your workout streak, exercise days, and rest periods.</p>
      </div>
      <div class="header-actions">
        <div class="avatar"><?= htmlspecialchars($user['name']) ?></div>
      </div>
    </header>

    <div class="streak-container">
      <div class="streak-panel">
        <div class="streak-panel-header">
          <h2>Plan Your Routine</h2>
          <p>Set how many days you want to train or recover this week.</p>
        </div>

        <form class="streak-form" method="POST" action="">
          <div class="streak-form-group">
            <label for="exercise_days">Exercise Days (Per Week)</label>
            <input type="number" id="exercise_days" name="exercise_days" min="1" max="7" placeholder="e.g. 5" value="<?= $exerciseDays ?>" required>
          </div>
          
          <div class="streak-form-group">
            <label for="rest_days">Rest Days (Per Week)</label>
            <input type="number" id="rest_days" name="rest_days" min="0" max="6" placeholder="e.g. 2" value="<?= $restDays ?>" required>
          </div>

          <button type="submit" class="streak-btn-submit">Save Routine</button>
        </form>
      </div>

      <div class="streak-info-card">
        <div class="info-icon">🔥</div>
        <h3>Current Status</h3>
        <p style="margin-bottom: 10px;">You are maintaining a strong <strong><?= $user['streak'] ?>-day</strong> fitness streak. Consistent planning prevents burnout!</p>
        
        <?php if ($showStatus): ?>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #16331c; width: 100%;">
            <p style="color: #ffffff; font-size: 0.95rem; margin: 5px 0;">🏋️‍♂️ Exercise: <strong><?= $exerciseDays ?> Days</strong></p>
            <p style="color: #ffffff; font-size: 0.95rem; margin: 5px 0;">🛌 Rest: <strong><?= $restDays ?> Days</strong></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>

</body>
</html>