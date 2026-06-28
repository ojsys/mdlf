<?php
/** @var string $content */
$s = settings();
$u = current_user();
$p = current_path();
$active = function($needle) use ($p) {
    return strpos($p, $needle) === 0 ? 'active' : '';
};
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Learning Portal') ?> — <?= e($s['site_short']) ?></title>
<?php if (!empty($s['favicon'])): ?>
  <link rel="icon" href="<?= upload_url($s['favicon']) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,560;1,9..144,400&family=Karla:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/styles.css') ?>?v=3">
</head>
<body>
<?php require __DIR__ . '/_flash.php'; ?>
<div class="shell">
  <aside class="side">
    <a class="brand" href="<?= url('/') ?>" style="color:#fff">
      <?php if (!empty($s['logo'])): ?>
        <img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="height:40px; margin-right:.7rem">
      <?php else: ?>
        <?php require __DIR__ . '/_mark.php'; ?>
      <?php endif; ?>
      <span class="brand-text"><b style="color:#fff"><?= e($s['site_short']) ?></b><span style="color:rgba(255,255,255,.5)">Disciple Portal</span></span>
    </a>
    <nav>
      <a class="<?= $p==='/portal'?'active':'' ?>" href="<?= url('portal') ?>">◆ My Journey</a>
      <a class="<?= $active('/portal/module')?'active':'' ?>" href="<?= url('discipleship') ?>">▤ All Modules</a>
      <a href="<?= url('our-work') ?>">◈ News &amp; Stories</a>
      <a href="<?= url('give') ?>">♥ Give</a>
    </nav>
    <div class="side-foot">
      <div class="pill" style="color:#fff; border-color:rgba(255,255,255,.2)"><?= e(explode(' ', $u['name'])[0]) ?></div>
      <a href="<?= url('portal/logout') ?>" style="display:block; color:rgba(255,255,255,.65); text-decoration:none; margin-top:.7rem; font-size:.88rem">Sign out →</a>
    </div>
  </aside>
  <div class="main">
    <div class="topbar">
      <h1><?= e($heading ?? 'My Journey') ?></h1>
      <span class="who">Signed in as <?= e($u['name']) ?></span>
    </div>
    <div class="content"><?= $content ?></div>
  </div>
</div>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
