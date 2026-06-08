<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FitTrack Profile</title>

<link rel="stylesheet" href="Profile.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="sidebar">

    <div class="logo">
        <h2>FitTrack Pro</h2>
        <span>Lose Fitness Journey</span>
    </div>

    <ul>
        <li><i class="fa-solid fa-house"></i> Dashboard</li>
        <li><i class="fa-solid fa-bullseye"></i> Goals</li>
        <li><i class="fa-solid fa-dumbbell"></i> Workouts</li>
        <li><i class="fa-solid fa-chart-line"></i> Analytics</li>

        <li class="active">
            <i class="fa-solid fa-user"></i> Profile
        </li>
    </ul>

    <div class="motivation">
        <h4>Keep Going!</h4>
        <p>You're on a 15-day streak.</p>
    </div>

</div>

<div class="main">

    <h2>Profile & Settings</h2>
    <p class="subtitle">Manage your account and preferences</p>

    <div class="profile-card">

        <div class="avatar">A</div>

        <div>
            <h3>ashfaksadik</h3>
            <p>Member since May 2025</p>

            <button>Edit Profile</button>
            <button>Change Photo</button>
        </div>

    </div>

    <div class="stats">

        <div class="box green">
            <h1>5</h1>
            <span>Active Goals</span>
        </div>

        <div class="box blue">
            <h1>28</h1>
            <span>Workouts This Month</span>
        </div>

        <div class="box purple">
            <h1>12</h1>
            <span>Achievements</span>
        </div>

    </div>

    <div class="card">

        <h3>Personal Information</h3>

        <input type="text" value="Ashfak Sadik">

        <input type="email" value="john@example.com">

        <div class="row">
            <input type="text" value="75">
            <input type="text" value="175">
        </div>

        <div class="row">
            <input type="text" value="28">

            <select>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>

        <button class="save">Save Changes</button>

    </div>

    <div class="card">

        <h3>Preferences</h3>

        <div class="setting">
            <span>Dark Mode</span>
            <input type="checkbox" checked>
        </div>

        <div class="setting">
            <span>Push Notifications</span>
            <input type="checkbox" checked>
        </div>

        <div class="setting">
            <span>Email Updates</span>
            <input type="checkbox" checked>
        </div>

    </div>

    <div class="achievements-card">

    <h2 class="title">
        Recent Achievements 🏆
    </h2>

    <div class="achievement-item">

        <div class="left">

            <div class="icon">
                <i class="fa-solid fa-award"></i>
            </div>

            <div>
                <h3>First Week Complete</h3>
                <p>Completed 7 days of workouts</p>
            </div>

        </div>

        <span class="date">May 5, 2026</span>

    </div>

    <div class="achievement-item">

        <div class="left">

            <div class="icon">
                <i class="fa-solid fa-award"></i>
            </div>

            <div>
                <h3>Calorie Crusher</h3>
                <p>Burned 5000+ calories in a week</p>
            </div>

        </div>

        <span class="date">May 8, 2026</span>

    </div>

    <div class="achievement-item">

        <div class="left">

            <div class="icon">
                <i class="fa-solid fa-award"></i>
            </div>

            <div>
                <h3>Early Bird</h3>
                <p>5 morning workouts</p>
            </div>

        </div>

        <span class="date">May 10, 2026</span>

    </div>

    <div class="achievement-item">

        <div class="left">

            <div class="icon">
                <i class="fa-solid fa-award"></i>
            </div>

            <div>
                <h3>Weight Loss Milestone</h3>
                <p>Lost 3kg</p>
            </div>

        </div>

        <span class="date">May 12, 2026</span>

    </div>

</div>

</div>

</body>
</html>