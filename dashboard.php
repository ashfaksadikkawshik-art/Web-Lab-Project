<?php

$weeklyData = [
    ["day" => "Mon", "calories" => 480],
    ["day" => "Tue", "calories" => 560],
    ["day" => "Wed", "calories" => 320],
    ["day" => "Thu", "calories" => 760],
    ["day" => "Fri", "calories" => 510],
    ["day" => "Sat", "calories" => 920],
    ["day" => "Sun", "calories" => 670],
];

$distributionData = [
    ["day" => "Mon", "sessions" => 0.75],
    ["day" => "Tue", "sessions" => 1.75],
    ["day" => "Wed", "sessions" => 0.50],
    ["day" => "Thu", "sessions" => 1.75],
    ["day" => "Fri", "sessions" => 1.25],
    ["day" => "Sat", "sessions" => 3.00],
    ["day" => "Sun", "sessions" => 1.50],
];

$goals = [
    [
        "label"   => "Calories",
        "current" => "2,450",
        "target"  => "2,800",
        "pct"     => 85,
        "color"   => "#10d9a0"
    ],

    [
        "label"   => "Steps",
        "current" => "6,500",
        "target"  => "10,000",
        "pct"     => 65,
        "color"   => "#3b82f6"
    ],

    [
        "label"   => "Water Intake",
        "current" => "2.7",
        "target"  => "3.0 L",
        "pct"     => 90,
        "color"   => "#06b6d4"
    ]
];

$achievements = [

    [
        "emoji" => "🏅",
        "color" => "#f59e0b",
        "label" => "7-Day Streak",
        "desc"  => "Worked out for 7 days straight"
    ],

    [
        "emoji" => "🏃",
        "color" => "#14b8a6",
        "label" => "5K Runner",
        "desc"  => "Completed a 5K run"
    ],

    [
        "emoji" => "🌅",
        "color" => "#8b5cf6",
        "label" => "Early Bird",
        "desc"  => "5 morning workouts this week"
    ]
];

$totalCalories = 0;

foreach ($weeklyData as $data) {
    $totalCalories += $data['calories'];
}

$totalSessions = 0;

foreach ($distributionData as $data) {
    $totalSessions += $data['sessions'];
}

$username = "Ashfak";
$streakDays = 12;
$goalProgress = 78;

header('Content-Type: application/json');

echo json_encode([

    "user" => [
        "name" => $username,
        "streak_days" => $streakDays,
        "goal_progress" => $goalProgress
    ],

    "weekly_data" => $weeklyData,

    "distribution_data" => $distributionData,

    "goals" => $goals,

    "achievements" => $achievements,

    "summary" => [
        "total_calories" => $totalCalories,
        "total_sessions" => $totalSessions
    ]

], JSON_PRETTY_PRINT);

?>