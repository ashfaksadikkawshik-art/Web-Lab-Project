<?php
session_start();
if (!isset($_SESSION['goals'])) {
    $_SESSION['goals'] = [
        ['title'=>'Weight Loss',     'type'=>'weight',   'icon'=>'↘', 'color'=>'green',  'current'=>'75 kg',       'target'=>'70 kg',        'pct'=>100, 'achieved'=>true ],
        ['title'=>'Muscle Gain',     'type'=>'muscle',   'icon'=>'↗', 'color'=>'blue',   'current'=>'65 kg',       'target'=>'72 kg',        'pct'=>90,  'achieved'=>false],
        ['title'=>'Daily Steps',     'type'=>'steps',    'icon'=>'👟','color'=>'purple', 'current'=>'6,500 steps', 'target'=>'10,000 steps', 'pct'=>65,  'achieved'=>false],
        ['title'=>'Water Intake',    'type'=>'water',    'icon'=>'💧','color'=>'cyan',   'current'=>'2.7 L',       'target'=>'3 L',          'pct'=>90,  'achieved'=>false],
        ['title'=>'Weekly Workouts', 'type'=>'workouts', 'icon'=>'✕', 'color'=>'orange', 'current'=>'4 sessions',  'target'=>'5 sessions',   'pct'=>80,  'achieved'=>false],
    ];
}
$errors = [];
$form   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_goal') {
    $form['title']   = trim($_POST['title']   ?? '');
    $form['type']    = trim($_POST['type']    ?? '');
    $form['current'] = trim($_POST['current'] ?? '');
    $form['target']  = trim($_POST['target']  ?? '');
    $form['pct']     = (int)($_POST['pct']    ?? 0);
    $form['color']   = $_POST['color'] ?? 'green';
    if ($form['title']   === '') $errors['title']   = 'Goal name is required.';
    if ($form['type']    === '') $errors['type']    = 'Category is required.';
    if ($form['current'] === '') $errors['current'] = 'Current value is required.';
    if ($form['target']  === '') $errors['target']  = 'Target value is required.';
    if ($form['pct'] < 0 || $form['pct'] > 100) $errors['pct'] = 'Progress must be 0–100.';
    $allowed_colors = ['green','blue','purple','cyan','orange'];
    if (!in_array($form['color'], $allowed_colors, true)) $form['color'] = 'green';
    $color_icons = ['green'=>'↘','blue'=>'↗','purple'=>'👟','cyan'=>'💧','orange'=>'✕'];
    if (empty($errors)) {
        $_SESSION['goals'][] = [
            'title'    => htmlspecialchars($form['title'],   ENT_QUOTES),
            'type'     => htmlspecialchars($form['type'],    ENT_QUOTES),
            'icon'     => $color_icons[$form['color']],
            'color'    => $form['color'],
            'current'  => htmlspecialchars($form['current'], ENT_QUOTES),
            'target'   => htmlspecialchars($form['target'],  ENT_QUOTES),
            'pct'      => $form['pct'],
            'achieved' => $form['pct'] >= 100,
        ];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

$goals    = $_SESSION['goals'];
$show_modal = !empty($errors);

$user       = ['name' => 'S', 'streak' => 12];
$nav_items  = ['Dashboard', 'Goals', 'Workouts', 'Analytics', 'Profile'];
$active_nav = 'Goals';

function ring_offset(int $pct): float {
    return 2 * M_PI * 54 * (1 - $pct / 100);
}
$circumference = round(2 * M_PI * 54, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack Pro – Goals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Goal.css">
</head>
<body>

<div class="app">
    <aside class="sidebar">
        <div class="brand">
            <span class="brand-icon">⚡</span>
            <div>
                <span class="brand-name">FitTrack Pro</span>
                <span class="brand-sub">Your Fitness Journey</span>
            </div>
        </div>
        <nav class="nav">
            <?php foreach ($nav_items as $item): ?>
                <a href="#" class="nav-item <?= $item === $active_nav ? 'active' : '' ?>">
                    <?= htmlspecialchars($item) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="streak-card">
            <span class="streak-emoji">💪</span>
            <strong class="streak-title">Keep Going!</strong>
            <p class="streak-body">
                You're on a <?= (int)$user['streak'] ?>-day streak.<br>
                Don't break it now!
            </p>
        </div>
    </aside>
    <main class="main">

        <header class="page-header">
            <div>
                <h1 class="page-title">Fitness Goals</h1>
                <p class="page-sub">Track and manage your fitness objectives</p>
            </div>
            <div class="header-actions">
                <div class="avatar"><?= htmlspecialchars($user['name']) ?></div>
                <button class="btn-new" id="openModal">+ New Goal</button>
            </div>
        </header>
        <div class="goals-grid">
            <?php foreach ($goals as $g): ?>
            <div class="card <?= $g['achieved'] ? 'card--achieved' : '' ?>">
                <div class="card-header">
                    <div>
                        <h2 class="card-title"><?= $g['title'] ?></h2>
                        <span class="card-type"><?= $g['type'] ?></span>
                    </div>
                    <span class="card-icon card-icon--<?= $g['color'] ?>"><?= $g['icon'] ?></span>
                </div>
                <div class="ring-wrap">
                    <svg class="ring" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                        <circle class="ring-track" cx="60" cy="60" r="54" fill="none" stroke-width="8"/>
                        <circle class="ring-progress ring-progress--<?= $g['color'] ?>"
                                cx="60" cy="60" r="54" fill="none" stroke-width="8"
                                stroke-linecap="round"
                                stroke-dasharray="<?= $circumference ?>"
                                stroke-dashoffset="<?= round(ring_offset($g['pct']), 2) ?>"
                                transform="rotate(-90 60 60)"
                                style="--offset:<?= round(ring_offset($g['pct']), 2) ?>;--circ:<?= $circumference ?>"/>
                    </svg>
                    <div class="ring-label">
                        <span class="ring-pct"><?= (int)$g['pct'] ?>%</span>
                        <span class="ring-word">Complete</span>
                    </div>
                </div>
                <div class="stats">
                    <div class="stat-row">
                        <span class="stat-key">Current</span>
                        <span class="stat-val"><?= $g['current'] ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-key">Target</span>
                        <span class="stat-val"><?= $g['target'] ?></span>
                    </div>
                </div>
                <div class="bar-track">
                    <div class="bar-fill bar-fill--<?= $g['color'] ?>" style="width:<?= (int)$g['pct'] ?>%"></div>
                </div>
                <?php if ($g['achieved']): ?>
                <div class="achieved-btn">🎉 Goal Achieved!</div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

    </main>
</div>

<div class="modal-overlay <?= $show_modal ? 'open' : '' ?>" id="modalOverlay">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalHeading">
        <div class="modal-header">
            <h2 class="modal-title" id="modalHeading">✦ New Goal</h2>
            <button class="modal-close" id="closeModal" aria-label="Close">✕</button>
        </div>
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" novalidate>
            <input type="hidden" name="action" value="add_goal">
            <div class="form-grid">
                <div class="field field--full">
                    <label class="field-label" for="f_title">Goal Name</label>
                    <input class="field-input <?= isset($errors['title']) ? 'field-input--err' : '' ?>"
                           type="text" id="f_title" name="title"
                           placeholder="e.g. Run a 5K"
                           value="<?= htmlspecialchars($form['title'] ?? '') ?>">
                    <?php if (isset($errors['title'])): ?>
                        <span class="field-err"><?= $errors['title'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label class="field-label" for="f_type">Category</label>
                    <input class="field-input <?= isset($errors['type']) ? 'field-input--err' : '' ?>"
                           type="text" id="f_type" name="type"
                           placeholder="e.g. cardio"
                           value="<?= htmlspecialchars($form['type'] ?? '') ?>">
                    <?php if (isset($errors['type'])): ?>
                        <span class="field-err"><?= $errors['type'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label class="field-label">Color</label>
                    <div class="color-picker">
                        <?php
                        $colors = ['green','blue','purple','cyan','orange'];
                        foreach ($colors as $c):
                            $checked = (($form['color'] ?? 'green') === $c) ? 'checked' : '';
                        ?>
                        <label class="color-swatch color-swatch--<?= $c ?>">
                            <input type="radio" name="color" value="<?= $c ?>" <?= $checked ?>>
                            <span></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="field">
                    <label class="field-label" for="f_current">Current Value</label>
                    <input class="field-input <?= isset($errors['current']) ? 'field-input--err' : '' ?>"
                           type="text" id="f_current" name="current"
                           placeholder="e.g. 3 km"
                           value="<?= htmlspecialchars($form['current'] ?? '') ?>">
                    <?php if (isset($errors['current'])): ?>
                        <span class="field-err"><?= $errors['current'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label class="field-label" for="f_target">Target Value</label>
                    <input class="field-input <?= isset($errors['target']) ? 'field-input--err' : '' ?>"
                           type="text" id="f_target" name="target"
                           placeholder="e.g. 5 km"
                           value="<?= htmlspecialchars($form['target'] ?? '') ?>">
                    <?php if (isset($errors['target'])): ?>
                        <span class="field-err"><?= $errors['target'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="field field--full">
                    <label class="field-label" for="f_pct">
                        Current Progress — <span id="pctDisplay"><?= $form['pct'] ?? 0 ?>%</span>
                    </label>
                    <input class="field-range" type="range"
                           id="f_pct" name="pct" min="0" max="100"
                           value="<?= $form['pct'] ?? 0 ?>">
                    <?php if (isset($errors['pct'])): ?>
                        <span class="field-err"><?= $errors['pct'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="cancelModal">Cancel</button>
                <button type="submit" class="btn-save">Save Goal</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const overlay  = document.getElementById('modalOverlay');
    const openBtn  = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn= document.getElementById('cancelModal');
    const range    = document.getElementById('f_pct');
    const display  = document.getElementById('pctDisplay');

    function openModal()  { overlay.classList.add('open');    document.body.style.overflow = 'hidden'; }
    function closeModal() { overlay.classList.remove('open'); document.body.style.overflow = ''; }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e){ if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });

    range.addEventListener('input', function(){ display.textContent = this.value + '%'; });
})();
</script>

</body>
</html>
