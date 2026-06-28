<?php
/** @var string $content */
$s = settings();
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Sign in') ?> — <?= e($s['site_short'] ?? 'MDLF') ?></title>
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
<div class="auth-wrap">
  <aside class="auth-aside">
    <a class="brand" href="<?= url('/') ?>" style="color:#fff">
      <?php if (!empty($s['logo'])): ?>
        <img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="height:40px; margin-right:.7rem">
      <?php else: ?>
        <?php require __DIR__ . '/_mark.php'; ?>
      <?php endif; ?>
      <span class="brand-text"><b style="color:#fff"><?= e($s['site_short']) ?></b><span>Leadership Foundation</span></span>
    </a>
    <p class="quote">&ldquo;<?= e($s['verse']) ?>&rdquo;
      <span style="display:block; font-family:var(--sans); font-style:normal; font-size:.74rem; letter-spacing:.18em; text-transform:uppercase; color:var(--gold-soft); margin-top:1rem"><?= e($s['verse_ref']) ?></span>
    </p>
    <p class="muted" style="color:rgba(247,248,251,.6); font-size:.9rem">A growing community of disciples making disciples across Nigeria and beyond.</p>
  </aside>
  <div class="auth-main">
    <div class="auth-card"><?= $content ?></div>
  </div>
</div>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
