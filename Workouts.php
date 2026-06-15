<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: Login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "fit");
if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}

$loggedInUser = $_SESSION['user'];

// ==================== AUTO CREATE TABLE ENGINE (Fixes Table Not Found Error) ====================
$table_init = "CREATE TABLE IF NOT EXISTS `workouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `duration` varchar(50) NOT NULL,
  `calories` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  `icon` varchar(10) NOT NULL,
  `color_class` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($conn, $table_init);

// ==================== REAL DATABASE INSERTION ENGINE ====================
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_workout'])){
    $category = mysqli_real_escape_string($conn, $_POST['workout_type']);
    $duration_num = mysqli_real_escape_string($conn, $_POST['duration']);
    $calories_num = mysqli_real_escape_string($conn, $_POST['calories']);
    $date_raw = mysqli_real_escape_string($conn, $_POST['date']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    // Store pure values and format gracefully during rendering
    $duration = $duration_num . " min";
    $calories = $calories_num . " cal";
    $date = date("M d, Y", strtotime($date_raw));
    
    $title = !empty($notes) ? $notes : $category . " Session";

    // Icon Mapping Configuration
    $icon = "❤️";
    $color_class = "icon-cardio";
    if($category == 'Strength') { $icon = "💪"; $color_class = "icon-strength"; }
    if($category == 'Cycling') { $icon = "🚴"; $color_class = "icon-cycling"; }
    if($category == 'Yoga') { $icon = "🧘"; $color_class = "icon-yoga"; }
    if($category == 'Running') { $icon = "🏃"; $color_class = "icon-running"; }

    $insert_query = "INSERT INTO workouts (user, category, title, duration, calories, date, icon, color_class) 
                     VALUES ('$loggedInUser', '$category', '$title', '$duration', '$calories', '$date', '$icon', '$color_class')";
    
    if(mysqli_query($conn, $insert_query)){
        // Dynamic redirection to current script filename safely
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// ==================== FETCH WORKOUTS FROM DATABASE ====================
$fetch_query = "SELECT * FROM workouts WHERE user = '$loggedInUser' ORDER BY id DESC";
$result = mysqli_query($conn, $fetch_query);

$workouts = [];
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $workouts[] = $row;
    }
} else {
    // Initial Fallback Data if table is brand new
    $workouts = [
        ["category" => "Cardio", "title" => "Morning run at the park", "duration" => "45 min", "calories" => "420 cal", "date" => "May 12, 2026", "icon" => "❤️", "color_class" => "icon-cardio"],
        ["category" => "Strength", "title" => "Upper body workout", "duration" => "60 min", "calories" => "350 cal", "date" => "May 11, 2026", "icon" => "💪", "color_class" => "icon-strength"],
        ["category" => "Cycling", "title" => "Evening bike ride", "duration" => "90 min", "calories" => "580 cal", "date" => "May 10, 2026", "icon" => "🚴", "color_class" => "icon-cycling"]
    ];
}

// Calculate Dynamic Stats Summary safely by striping text characters
$total_calories = 0;
$total_minutes = 0;
$this_week_count = count($workouts);

foreach($workouts as $w){
    $total_calories += (int)filter_var($w['calories'], FILTER_SANITIZE_NUMBER_INT);
    $total_minutes += (int)filter_var($w['duration'], FILTER_SANITIZE_NUMBER_INT);
}

$hours = floor($total_minutes / 60);
$mins = $total_minutes % 60;
$duration_string = ($hours > 0) ? $hours . "h " . $mins . "m" : $mins . " min";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack Pro – Workout Log</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Workouts.css">
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
      <a href="Dashboard.php" class="nav-item">Dashboard</a>
      <a href="Goal.php" class="nav-item">Goals</a>
      <a href="Workouts.php" class="nav-item active">Workouts</a>
      <a href="Analytics.php" class="nav-item">Analytics</a>
      <a href="Profile.php" class="nav-item">Profile</a>
    </nav>

    <div class="motiv-card">
      <div class="title">💪 Keep Going!</div>
      <div class="sub">You're on a 12-day streak.<br>Don't break it now!</div>
    </div>
  </aside>

  <main class="main">
    <header class="page-header">
      <div>
        <h1 class="page-title">Workout Log</h1>
        <p class="page-sub">Track and manage your exercise sessions</p>
      </div>
      <button class="btn-log-workout" id="openModalBtn">+ Log Workout</button>
    </header>

    <div class="stat-grid">
      <div class="stat-card border-orange">
        <div class="stat-inner">
          <span class="stat-emoji">🔥</span>
          <div>
            <div class="stat-label">Total Calories</div>
            <div class="stat-value"><?= number_format($total_calories) ?> cal</div>
          </div>
        </div>
      </div>
      <div class="stat-card border-blue">
        <div class="stat-inner">
          <span class="stat-emoji">🕒</span>
          <div>
            <div class="stat-label">Total Duration</div>
            <div class="stat-value"><?= $duration_string ?></div>
          </div>
        </div>
      </div>
      <div class="stat-card border-green">
        <div class="stat-inner">
          <span class="stat-emoji">📈</span>
          <div>
            <div class="stat-label">Total Logged</div>
            <div class="stat-value"><?= $this_week_count ?> sessions</div>
          </div>
        </div>
      </div>
    </div>

    <div class="panel log-panel">
      <div class="filter-bar">
        <div class="search-wrapper">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Search workouts..." class="search-input">
        </div>
        <div class="filter-buttons">
          <button class="filter-btn active">All</button>
          <button class="filter-btn">Cardio</button>
          <button class="filter-btn">Strength</button>
          <button class="filter-btn">Running</button>
          <button class="filter-btn">Yoga</button>
          <button class="filter-btn">Cycling</button>
        </div>
      </div>

      <div class="workout-list">
        <?php foreach($workouts as $workout): ?>
        <div class="workout-item">
          <div class="workout-left">
            <div class="workout-icon <?= htmlspecialchars($workout['color_class']) ?>">
              <?= htmlspecialchars($workout['icon']) ?>
            </div>
            <div>
              <h3 class="workout-title"><?= htmlspecialchars($workout['category']) ?></h3>
              <p class="workout-subtext"><?= htmlspecialchars($workout['title']) ?></p>
              <div class="workout-meta">
                <span>⏱️ <?= htmlspecialchars($workout['duration']) ?></span>
                <span>🔥 <?= htmlspecialchars($workout['calories']) ?></span>
              </div>
            </div>
          </div>
          <span class="workout-date"><?= htmlspecialchars($workout['date']) ?></span>
        </div>
        <?php endforeach; ?>
        
        <div class="no-results-message" style="display: none;">
          No workouts found matching your query.
        </div>
      </div>
    </div>
  </main>
</div>

<!-- MODAL POPUP FORM -->
<div class="modal-overlay" id="workoutModal">
  <div class="modal-box">
    <h2 class="modal-title">Log New Workout</h2>
    
    <form action="" method="POST">
      <div class="modal-field">
        <label>Workout Type</label>
        <div class="select-wrapper">
          <select name="workout_type" required>
            <option value="Cardio">Cardio</option>
            <option value="Strength">Strength</option>
            <option value="Running">Running</option>
            <option value="Yoga">Yoga</option>
            <option value="Cycling">Cycling</option>
          </select>
        </div>
      </div>

      <div class="modal-row">
        <div class="modal-field">
          <label>Duration (min)</label>
          <input type="number" name="duration" min="1" value="45" required>
        </div>
        <div class="modal-field">
          <label>Calories</label>
          <input type="number" name="calories" min="1" value="350" required>
        </div>
      </div>

      <div class="modal-field">
        <label>Date</label>
        <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="modal-field">
        <label>Workout Title / Notes</label>
        <textarea name="notes" placeholder="e.g. Morning run at the park, Upper body blast..."></textarea>
      </div>

      <div class="modal-actions">
        <button type="submit" name="save_workout" class="btn-modal-save">Save Workout</button>
        <button type="button" class="btn-modal-cancel" id="closeModalBtn">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('workoutModal');
  const openBtn = document.getElementById('openModalBtn');
  const closeBtn = document.getElementById('closeModalBtn');

  openBtn.addEventListener('click', () => { modal.classList.add('active'); });
  closeBtn.addEventListener('click', () => { modal.classList.remove('active'); });
  modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('active'); });

  const searchInput = document.querySelector('.search-input');
  const filterButtons = document.querySelectorAll('.filter-btn');
  const workoutItems = document.querySelectorAll('.workout-item');
  const noResultsMsg = document.querySelector('.no-results-message');

  let activeCategory = 'all';

  function filterEngine() {
    const queryText = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    workoutItems.forEach(card => {
      const categoryTitle = card.querySelector('.workout-title').textContent.toLowerCase();
      const paragraphDesc = card.querySelector('.workout-subtext').textContent.toLowerCase();

      const matchSearch = categoryTitle.includes(queryText) || paragraphDesc.includes(queryText);
      const matchCategory = (activeCategory === 'all') || categoryTitle.includes(activeCategory);

      if (matchSearch && matchCategory) {
        card.style.display = 'flex';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    noResultsMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
  }

  searchInput.addEventListener('input', filterEngine);

  filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeCategory = btn.textContent.toLowerCase().trim();
      filterEngine();
    });
  });
</script>

</body>
</html>