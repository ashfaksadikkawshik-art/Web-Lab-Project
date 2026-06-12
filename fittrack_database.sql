-- ============================================================
--  FitTrack Pro — Full Database Schema + Sample Data
--  Compatible with: MySQL 5.7+ / MariaDB 10.3+
-- ============================================================

CREATE DATABASE IF NOT EXISTS fittrack_pro
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE fittrack_pro;

-- ============================================================
-- 1. USERS
-- ============================================================
CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100)        NOT NULL,
  email         VARCHAR(150)        NOT NULL UNIQUE,
  password_hash VARCHAR(255)        NOT NULL,
  gender        ENUM('male','female','other') DEFAULT 'male',
  birth_date    DATE,
  height_cm     DECIMAL(5,2),
  start_weight  DECIMAL(5,2),          -- kg at registration
  goal_weight   DECIMAL(5,2),
  avatar_url    VARCHAR(255),
  created_at    TIMESTAMP           DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP           DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- 2. WEIGHT LOG  (one entry per day per user)
-- ============================================================
CREATE TABLE weight_log (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  logged_at  DATE         NOT NULL,
  weight_kg  DECIMAL(5,2) NOT NULL,
  note       VARCHAR(255),
  UNIQUE KEY uq_user_date (user_id, logged_at),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 3. WORKOUT CATEGORIES
-- ============================================================
CREATE TABLE workout_categories (
  id    TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(50) NOT NULL UNIQUE   -- Cardio, Strength, Yoga, Cycling, Running
);

-- ============================================================
-- 4. WORKOUTS  (each session)
-- ============================================================
CREATE TABLE workouts (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED     NOT NULL,
  category_id    TINYINT UNSIGNED NOT NULL,
  workout_date   DATE             NOT NULL,
  start_time     TIME,
  duration_min   SMALLINT UNSIGNED NOT NULL,   -- minutes
  calories_burned SMALLINT UNSIGNED NOT NULL,
  notes          VARCHAR(255),
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)     REFERENCES users(id)              ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES workout_categories(id) ON DELETE RESTRICT
);

-- ============================================================
-- 5. ACHIEVEMENTS
-- ============================================================
CREATE TABLE achievements (
  id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(100) NOT NULL,
  description VARCHAR(255),
  icon        VARCHAR(50)              -- emoji or icon name
);

CREATE TABLE user_achievements (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED      NOT NULL,
  achievement_id SMALLINT UNSIGNED NOT NULL,
  unlocked_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_ach (user_id, achievement_id),
  FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
  FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
);

-- ============================================================
-- 6. GOALS
-- ============================================================
CREATE TABLE goals (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL,
  goal_type    ENUM('weight','calories','workouts','streak') NOT NULL,
  target_value DECIMAL(8,2) NOT NULL,
  start_date   DATE         NOT NULL,
  end_date     DATE,
  is_completed TINYINT(1)   DEFAULT 0,
  created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 7. STREAKS
-- ============================================================
CREATE TABLE streaks (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL UNIQUE,
  current_days  SMALLINT UNSIGNED DEFAULT 0,
  best_days     SMALLINT UNSIGNED DEFAULT 0,
  last_active   DATE,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- ============================================================
--  SAMPLE DATA
-- ============================================================
-- ============================================================

-- ── Workout Categories ──────────────────────────────────────
INSERT INTO workout_categories (name) VALUES
  ('Cardio'),
  ('Strength'),
  ('Yoga'),
  ('Cycling'),
  ('Running');

-- ── Achievements catalogue ──────────────────────────────────
INSERT INTO achievements (title, description, icon) VALUES
  ('First Step',        'Complete your first workout',              '👟'),
  ('Week Warrior',      'Work out 7 days in a row',                 '⚔️'),
  ('Calorie Crusher',   'Burn 500+ cal in a single session',        '🔥'),
  ('Iron Will',         'Complete 10 strength sessions',            '🏋️'),
  ('Zen Master',        'Complete 5 yoga sessions',                 '🧘'),
  ('Century Club',      'Log 100 workouts total',                   '💯'),
  ('Speed Demon',       'Run 5 km in under 30 min',                 '⚡'),
  ('Consistency King',  'Work out for 30 consecutive days',         '👑'),
  ('Burn Machine',      'Burn 10,000 cal in a month',               '🌋'),
  ('Mountain Climber',  'Complete a 120-min workout',               '⛰️'),
  ('Night Owl',         'Complete 5 workouts after 9 PM',           '🦉'),
  ('Early Bird',        'Complete 5 workouts before 7 AM',          '🐦');

-- ── Users ───────────────────────────────────────────────────
INSERT INTO users (name, email, password_hash, gender, birth_date, height_cm, start_weight, goal_weight) VALUES
  ('Alex Rahman',   'alex@example.com',   '$2y$10$examplehashalex123',   'male',   '1995-03-12', 175.0, 82.0, 74.0),
  ('Sara Islam',    'sara@example.com',   '$2y$10$examplehashsara456',   'female', '1998-07-25', 162.0, 68.0, 60.0),
  ('James Hossain', 'james@example.com',  '$2y$10$examplehashjames789',  'male',   '1990-11-08', 180.0, 90.0, 80.0);

-- ── Streaks ─────────────────────────────────────────────────
INSERT INTO streaks (user_id, current_days, best_days, last_active) VALUES
  (1, 12, 14, CURDATE()),
  (2,  5,  9, CURDATE()),
  (3,  3, 20, CURDATE());

-- ── Goals ───────────────────────────────────────────────────
INSERT INTO goals (user_id, goal_type, target_value, start_date, end_date, is_completed) VALUES
  (1, 'weight',   74.0,  '2026-05-01', '2026-07-31', 0),
  (1, 'workouts', 30,    '2026-05-01', '2026-05-31', 0),
  (2, 'calories', 15000, '2026-05-01', '2026-05-31', 1),
  (3, 'weight',   80.0,  '2026-04-01', '2026-06-30', 0);

-- ── Weight Log — User 1 (6 weeks: Apr 28 → Jun 8) ───────────
INSERT INTO weight_log (user_id, logged_at, weight_kg) VALUES
  (1, '2026-04-28', 79.0),
  (1, '2026-05-05', 78.4),
  (1, '2026-05-12', 77.8),
  (1, '2026-05-19', 77.1),
  (1, '2026-05-26', 76.3),
  (1, '2026-06-02', 76.0);

-- ── Weight Log — User 2 ──────────────────────────────────────
INSERT INTO weight_log (user_id, logged_at, weight_kg) VALUES
  (2, '2026-04-28', 68.0),
  (2, '2026-05-05', 67.3),
  (2, '2026-05-12', 66.8),
  (2, '2026-05-19', 66.1),
  (2, '2026-05-26', 65.5),
  (2, '2026-06-02', 65.0);

-- ── Workouts — User 1 (full May 2026) ───────────────────────
INSERT INTO workouts (user_id, category_id, workout_date, start_time, duration_min, calories_burned, notes) VALUES
-- Week 1
(1, 1, '2026-05-01', '07:00:00', 55, 480, 'Morning run + treadmill'),
(1, 2, '2026-05-02', '07:30:00', 50, 370, 'Upper body push'),
(1, 5, '2026-05-03', '08:00:00', 45, 390, 'Outdoor run 5k'),
(1, 1, '2026-05-04', '07:00:00', 60, 520, 'HIIT cardio'),
(1, 3, '2026-05-05', '18:00:00', 40, 200, 'Evening yoga flow'),
-- Week 2
(1, 4, '2026-05-06', '07:00:00', 55, 430, 'Cycling intervals'),
(1, 2, '2026-05-07', '07:30:00', 60, 410, 'Lower body strength'),
(1, 1, '2026-05-08', '06:45:00', 65, 560, 'Long cardio session'),
(1, 5, '2026-05-09', '08:00:00', 40, 350, 'Easy run'),
(1, 3, '2026-05-10', '17:30:00', 45, 180, 'Yoga & stretch'),
(1, 2, '2026-05-12', '07:00:00', 55, 420, 'Full body strength'),
-- Week 3 (Peak)
(1, 1, '2026-05-13', '06:30:00', 70, 620, 'Peak cardio – best session'),
(1, 2, '2026-05-14', '07:00:00', 60, 450, 'Chest & back'),
(1, 5, '2026-05-15', '07:30:00', 50, 440, 'Tempo run'),
(1, 4, '2026-05-16', '08:00:00', 60, 480, 'Cycling endurance'),
(1, 1, '2026-05-17', '07:00:00', 75, 640, 'Cardio blast'),
(1, 2, '2026-05-18', '08:00:00', 55, 400, 'Arms & shoulders'),
(1, 3, '2026-05-19', '18:00:00', 40, 190, 'Recovery yoga'),
-- Week 4
(1, 1, '2026-05-20', '07:00:00', 60, 510, 'Treadmill intervals'),
(1, 5, '2026-05-21', '07:30:00', 45, 390, 'Morning run'),
(1, 2, '2026-05-22', '07:00:00', 55, 430, 'Leg day'),
(1, 4, '2026-05-23', '08:00:00', 50, 400, 'Cycling'),
(1, 1, '2026-05-24', '07:00:00', 60, 520, 'Cardio session'),
-- Week 5
(1, 2, '2026-05-26', '07:30:00', 50, 380, 'Pull day'),
(1, 5, '2026-05-27', '08:00:00', 40, 350, 'Easy jog'),
(1, 1, '2026-05-28', '07:00:00', 60, 500, 'HIIT'),
(1, 3, '2026-05-29', '18:30:00', 40, 170, 'Yoga'),
(1, 4, '2026-05-30', '07:00:00', 55, 440, 'Cycling'),
(1, 2, '2026-05-31', '07:30:00', 50, 410, 'Strength finisher'),
-- June
(1, 1, '2026-06-02', '07:00:00', 55, 470, 'June kickoff cardio'),
(1, 5, '2026-06-03', '07:30:00', 45, 395, 'Morning run'),
(1, 2, '2026-06-04', '08:00:00', 50, 415, 'Push day'),
(1, 1, '2026-06-05', '07:00:00', 60, 490, 'Intervals'),
(1, 4, '2026-06-06', '07:30:00', 55, 435, 'Cycling'),
(1, 3, '2026-06-07', '18:00:00', 40, 185, 'Evening yoga'),
-- Longest workout record
(1, 1, '2026-05-13', '09:00:00', 120, 880, 'Record: longest + most calories');

-- ── Workouts — User 2 ────────────────────────────────────────
INSERT INTO workouts (user_id, category_id, workout_date, start_time, duration_min, calories_burned) VALUES
(2, 3, '2026-05-03', '08:00:00', 50, 220),
(2, 1, '2026-05-05', '07:00:00', 45, 380),
(2, 3, '2026-05-10', '08:00:00', 55, 240),
(2, 1, '2026-05-12', '07:30:00', 50, 400),
(2, 2, '2026-05-17', '09:00:00', 45, 300),
(2, 5, '2026-05-20', '07:00:00', 40, 330),
(2, 3, '2026-05-24', '08:00:00', 50, 210),
(2, 1, '2026-05-27', '07:00:00', 55, 450);

-- ── Workouts — User 3 ────────────────────────────────────────
INSERT INTO workouts (user_id, category_id, workout_date, start_time, duration_min, calories_burned) VALUES
(3, 2, '2026-05-02', '08:00:00', 60, 500),
(3, 2, '2026-05-05', '08:30:00', 65, 530),
(3, 4, '2026-05-08', '07:00:00', 70, 560),
(3, 1, '2026-05-11', '07:30:00', 55, 480),
(3, 2, '2026-05-14', '08:00:00', 60, 510),
(3, 5, '2026-05-17', '07:00:00', 50, 420),
(3, 2, '2026-05-20', '08:30:00', 65, 540),
(3, 4, '2026-05-23', '07:00:00', 60, 490),
(3, 1, '2026-05-26', '07:30:00', 55, 460),
(3, 2, '2026-05-29', '08:00:00', 60, 520);

-- ── User Achievements — User 1 ───────────────────────────────
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
  (1,  1, '2026-04-15 08:00:00'),
  (1,  2, '2026-04-22 09:00:00'),
  (1,  3, '2026-05-13 10:00:00'),
  (1,  4, '2026-05-18 08:00:00'),
  (1,  5, '2026-05-19 19:00:00'),
  (1,  7, '2026-05-15 08:30:00'),
  (1,  9, '2026-05-28 08:00:00'),
  (1, 10, '2026-05-13 11:00:00'),
  (1, 11, '2026-04-30 21:00:00'),
  (1, 12, '2026-05-07 06:30:00'),
  (1,  6, '2026-05-31 08:00:00'),
  (1,  8, '2026-06-07 08:00:00');

-- ── User Achievements — Users 2 & 3 ─────────────────────────
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
  (2, 1, '2026-05-03 08:30:00'),
  (2, 5, '2026-05-24 09:00:00'),
  (2, 2, '2026-05-10 08:00:00'),
  (3, 1, '2026-05-02 09:00:00'),
  (3, 4, '2026-05-20 09:00:00'),
  (3, 3, '2026-05-14 09:00:00');


-- ============================================================
--  USEFUL VIEWS  (power the analytics.php page)
-- ============================================================

-- Monthly summary stats for a user
CREATE OR REPLACE VIEW v_monthly_summary AS
SELECT
  user_id,
  DATE_FORMAT(workout_date, '%Y-%m')    AS month,
  COUNT(*)                              AS total_workouts,
  ROUND(AVG(duration_min), 0)           AS avg_duration_min,
  ROUND(AVG(calories_burned), 0)        AS avg_calories,
  SUM(calories_burned)                  AS total_calories,
  SUM(CASE WHEN category_id = 1 THEN duration_min ELSE 0 END) AS cardio_min,
  SUM(CASE WHEN category_id = 2 THEN duration_min ELSE 0 END) AS strength_min,
  SUM(CASE WHEN category_id = 3 THEN duration_min ELSE 0 END) AS yoga_min,
  SUM(CASE WHEN category_id = 4 THEN duration_min ELSE 0 END) AS cycling_min,
  SUM(CASE WHEN category_id = 5 THEN duration_min ELSE 0 END) AS running_min
FROM workouts
GROUP BY user_id, DATE_FORMAT(workout_date, '%Y-%m');

-- Personal records for a user
CREATE OR REPLACE VIEW v_personal_records AS
SELECT
  user_id,
  MAX(duration_min)    AS longest_workout_min,
  MAX(calories_burned) AS most_calories_session,
  SUM(duration_min)    AS total_time_min
FROM workouts
GROUP BY user_id;

-- Most active day of the week
CREATE OR REPLACE VIEW v_most_active_day AS
SELECT
  user_id,
  DAYNAME(workout_date)          AS day_name,
  COUNT(*)                       AS session_count
FROM workouts
GROUP BY user_id, DAYNAME(workout_date)
ORDER BY user_id, session_count DESC;

-- Calories burned by type (last 7 days, for area chart)
CREATE OR REPLACE VIEW v_calories_by_type_weekly AS
SELECT
  w.user_id,
  w.workout_date,
  c.name                         AS category,
  SUM(w.calories_burned)         AS total_calories
FROM workouts w
JOIN workout_categories c ON c.id = w.category_id
WHERE w.workout_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY w.user_id, w.workout_date, c.name;


-- ============================================================
--  QUICK VERIFICATION QUERIES
-- ============================================================
-- SELECT * FROM v_monthly_summary WHERE user_id = 1 AND month = '2026-05';
-- SELECT * FROM v_personal_records WHERE user_id = 1;
-- SELECT * FROM v_most_active_day WHERE user_id = 1 LIMIT 1;
-- SELECT COUNT(*) AS achievements_unlocked FROM user_achievements WHERE user_id = 1;
-- SELECT current_days, best_days FROM streaks WHERE user_id = 1;
