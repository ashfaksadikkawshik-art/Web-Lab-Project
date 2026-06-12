<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FitTrack Pro - Analytics</title>
  <link rel="stylesheet" href="analytics.css">
</head>
<body>

  <div class="dashboard-container">
    
    <aside class="sidebar">
      <div class="logo">⚡ FitTrack Pro</div>
      <nav class="nav-links">
        <a href="#">Dashboard</a>
        <a href="#">Goals</a>
        <a href="#">Workouts</a>
        <a href="#" class="active">Analytics</a>
        <a href="#">Profile</a>
      </nav>
      <div class="streak-card">
        <p><strong>Keep Going!</strong></p>
        <p style="font-size: 12px; color: #aaa; margin-top: 5px;">You're on a 12-day streak.</p>
      </div>
    </aside>

    <main class="main-content">
      <header class="content-header">
        <h1>Analytics & Progress</h1>
        <p>Detailed insights into your fitness journey</p>
      </header>

      <section class="stats-grid">
        <div class="stat-card">
          <span class="stat-title">Weight Lost</span>
          <h2>3.0 kg</h2>
          <span class="stat-sub green-text">↓ 3.8% from start</span>
        </div>
        <div class="stat-card">
          <span class="stat-title">Total Calories</span>
          <h2>18,420</h2>
          <span class="stat-sub orange-text">This month</span>
        </div>
        <div class="stat-card">
          <span class="stat-title">Workouts</span>
          <h2>28</h2>
          <span class="stat-sub blue-text">This month</span>
        </div>
        <div class="stat-card">
          <span class="stat-title">Achievements</span>
          <h2>12</h2>
          <span class="stat-sub purple-text">Unlocked</span>
        </div>
      </section>

      <section class="charts-grid">
        <div class="chart-card">
          <h3>Weight Progress (6 Weeks)</h3>
          <div class="mock-line-chart">
            <div class="line"></div>
          </div>
          <div class="chart-labels">
            <span>W1</span><span>W2</span><span>W3</span><span>W4</span><span>W5</span><span>W6</span>
          </div>
        </div>

        <div class="chart-card">
          <h3>Calories Burned by Type</h3>
          <div class="mock-area-chart">
            <div class="area-strength"></div>
            <div class="area-cardio"></div>
          </div>
          <div class="chart-legend">
            <span class="legend-cardio">● Cardio</span>
            <span class="legend-strength">● Strength</span>
          </div>
        </div>
      </section>

      <section class="large-chart-card">
        <h3>Workout Time by Category (This Month)</h3>
        <div class="bar-group"><label>Cardio</label><div class="bar-container"><div class="bar" style="width: 95%;"></div></div></div>
        <div class="bar-group"><label>Strength</label><div class="bar-container"><div class="bar" style="width: 80%;"></div></div></div>
        <div class="bar-group"><label>Yoga</label><div class="bar-container"><div class="bar" style="width: 55%;"></div></div></div>
        <div class="bar-group"><label>Cycling</label><div class="bar-container"><div class="bar" style="width: 65%;"></div></div></div>
        <div class="bar-group"><label>Running</label><div class="bar-container"><div class="bar" style="width: 60%;"></div></div></div>
      </section>

      <section class="summary-grid">
        <div class="summary-card">
          <h3>Monthly Summary</h3>
          <div class="row"><span>Total Workouts</span><strong>28 sessions</strong></div>
          <div class="row"><span>Avg. Duration</span><strong>52 min</strong></div>
          <div class="row"><span>Avg. Calories</span><strong>658 cal</strong></div>
        </div>
        <div class="summary-card">
          <h3>Most Active</h3>
          <div class="row"><span>Workout Type</span><strong class="green-text">Cardio</strong></div>
          <div class="row"><span>Day of Week</span><strong class="green-text">Saturday</strong></div>
          <div class="row"><span>Time of Day</span><strong class="green-text">Morning</strong></div>
        </div>
        <div class="summary-card">
          <h3>Personal Records</h3>
          <div class="row"><span>Longest Workout</span><strong class="green-text">120 min</strong></div>
          <div class="row"><span>Most Calories</span><strong class="green-text">880 cal</strong></div>
          <div class="row"><span>Best Streak</span><strong class="green-text">14 days</strong></div>
        </div>
      </section>

    </main>
  </div>

</body>
</html>