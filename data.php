<?php
// 1. DATABASE CONNECTION
$host    = 'localhost';
$db      = 'fittrack_pro';
$user    = 'root';       
$pass    = '';           
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Target User ID 1 (Alex Rahman)
$user_id = 1; 

// ==========================================
// 1. WEIGHT LOST QUERY
// ==========================================
// Pulls the starting weight and the most recent logged weight to calculate progress
$weightQuery = $pdo->prepare("
    SELECT 
        (u.start_weight - wl.weight_kg) AS weight_lost,
        u.start_weight
    FROM users u
    JOIN weight_log wl ON u.id = wl.user_id
    WHERE u.id = ?
    ORDER BY wl.logged_at DESC LIMIT 1
");
$weightQuery->execute([$user_id]);
$weightRow = $weightQuery->fetch();

$weight_lost = isset($weightRow['weight_lost']) ? round($weightRow['weight_lost'], 1) : 0;
$start_weight = $weightRow['start_weight'] ?? 1; // Avoid division by zero
$loss_percentage = round(($weight_lost / $start_weight) * 100, 1);


// ==========================================
// 2. TOTAL WORKOUTS & CALORIES (This Month)
// ==========================================
// Counts workouts and adds up total calories for May 2026
$monthQuery = $pdo->prepare("
    SELECT 
        COUNT(*) AS total_workouts,
        SUM(calories_burned) AS total_calories,
        ROUND(AVG(duration_min), 0) AS avg_duration,
        ROUND(AVG(calories_burned), 0) AS avg_calories
    FROM workouts 
    WHERE user_id = ? AND workout_date LIKE '2026-05%'
");
$monthQuery->execute([$user_id]);
$monthData = $monthQuery->fetch();

$total_workouts  = $monthData['total_workouts'] ?? 0;
$total_calories  = $monthData['total_calories'] ?? 0;
$avg_duration    = $monthData['avg_duration'] ?? 0;
$avg_calories    = $monthData['avg_calories'] ?? 0;


// ==========================================
// 3. TOTAL ACHIEVEMENTS UNLOCKED
// ==========================================
// Counts how many rows exist for this user in the unlocked history table
$achieveQuery = $pdo->prepare("SELECT COUNT(*) AS total FROM user_achievements WHERE user_id = ?");
$achieveQuery->execute([$user_id]);
$totalAchievements = $achieveQuery->fetch()['total'] ?? 0;


// ==========================================
// 4. STREAK DATA
// ==========================================
// Selects the day streaks directly from the streaks table
$streakQuery = $pdo->prepare("SELECT current_days, best_days FROM streaks WHERE user_id = ?");
$streakQuery->execute([$user_id]);
$streakData = $streakQuery->fetch() ?: ['current_days' => 0, 'best_days' => 0];


// ==========================================
// 5. WORKOUT TIME BY CATEGORY (Progress Bars)
// ==========================================
// Sums total active minutes grouped by category IDs (1=Cardio, 2=Strength, etc.)
$barQuery = $pdo->prepare("
    SELECT category_id, SUM(duration_min) AS total_minutes 
    FROM workouts 
    WHERE user_id = ? AND workout_date LIKE '2026-05%'
    GROUP BY category_id
");
$barQuery->execute([$user_id]);
$barRows = $barQuery->fetchAll();

// Map the numeric category IDs to text names and calculate a clean display percentage
$max_scale = 340; 
$workouts_by_category = ["Cardio" => 0, "Strength" => 0, "Yoga" => 0, "Cycling" => 0, "Running" => 0];
$id_map = [1 => "Cardio", 2 => "Strength", 3 => "Yoga", 4 => "Cycling", 5 => "Running"];

foreach ($barRows as $row) {
    $cat_name = $id_map[$row['category_id']];
    $workouts_by_category[$cat_name] = min(100, round(($row['total_minutes'] / $max_scale) * 100));
}


// ==========================================
// 6. PERSONAL RECORDS
// ==========================================
// Finds the maximum values inside the workout table columns
$recordsQuery = $pdo->prepare("
    SELECT MAX(duration_min) AS longest, MAX(calories_burned) AS most_cal 
    FROM workouts 
    WHERE user_id = ?
");
$recordsQuery->execute([$user_id]);
$recordsData = $recordsQuery->fetch();

$longest_workout = $recordsData['longest'] ?? 0;
$most_calories   = $recordsData['most_cal'] ?? 0;
?>
