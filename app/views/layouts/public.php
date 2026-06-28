<?php
/** @var string $content */
$s = settings();
$p = current_path();
$is = fn($path) => $p === $path ? 'active' : '';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? $s['site_name'] ?? 'MDLF') ?> — <?= e($s['site_short'] ?? 'MDLF') ?></title>
<meta name="description" content="<?= e($s['tagline'] ?? '') ?>">
<?php if (!empty($s['favicon'])): ?>
  <link rel="icon" href="<?= upload_url($s['favicon']) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,560;1,9..144,400&family=Karla:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/styles.css') ?>?v=3">
<?php $editing = !empty($GLOBALS['APP']['editing']); ?>
<?php if (is_admin()): ?>
  <link rel="stylesheet" href="<?= asset('css/builder.css') ?>?v=1">
<?php endif; ?>
<?php if ($editing): ?>
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/media.css') ?>?v=1">
<?php endif; ?>
</head>
<body<?= is_admin() ? ' class="has-adminbar"' : '' ?>>
<?php require __DIR__ . '/_adminbar.php'; ?>
<?php require __DIR__ . '/_flash.php'; ?>

<header class="nav">
  <div class="wrap nav-inner">
    <a class="brand" href="<?= url('/') ?>">
      <?php if (!empty($s['logo'])): ?>
        <img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="height:40px; margin-right:.7rem">
      <?php else: ?>
        <?php require __DIR__ . '/_mark.php'; ?>
      <?php endif; ?>
      <span class="brand-text">
        <b><?= e($s['site_name'] ?? ($s['site_short'] ?? 'MDLF')) ?></b>
        <?php if (($s['brand_subtitle_visible'] ?? 'yes') !== 'no'): ?>
          <span><?= e($s['brand_subtitle'] ?? 'Leadership Foundation') ?></span>
        <?php endif; ?>
      </span>
    </a>
    <button class="nav-toggle" aria-label="Menu"><span></span><span></span><span></span></button>
    <nav class="nav-links">
      <a class="<?= $is('/about') ?>" href="<?= url('about') ?>">About</a>
      <a class="<?= $is('/our-work') ?>" href="<?= url('our-work') ?>">Our Work</a>
      <a class="<?= $is('/discipleship') ?>" href="<?= url('discipleship') ?>">Discipleship</a>
      <a class="<?= $is('/give') ?>" href="<?= url('give') ?>">Give</a>
      <a class="<?= $is('/contact') ?>" href="<?= url('contact') ?>">Contact</a>
      <a class="btn btn-primary btn-sm" href="<?= url('portal') ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14"></path>
          <path d="m12 5 7 7-7 7"></path>
        </svg>
        Learning Portal
      </a>
    </nav>
  </div>
</header>

<main><?= $content ?></main>

<footer class="footer">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <div class="brand" style="margin-bottom:1rem">
          <?php if (!empty($s['logo'])): ?>
            <img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="height:40px; margin-right:.7rem">
          <?php else: ?>
            <?php require __DIR__ . '/_mark.php'; ?>
          <?php endif; ?>
          <span class="brand-text"><b style="font-size:1.1rem"><?= e($s['site_name']) ?></b></span>
        </div>
        <p style="max-width:34ch; font-size:.95rem"><?= e($s['tagline']) ?></p>
      </div>
      <div>
        <h4>Explore</h4>
        <a href="<?= url('about') ?>">About</a>
        <a href="<?= url('our-work') ?>">Our Work</a>
        <a href="<?= url('discipleship') ?>">Discipleship</a>
        <a href="<?= url('give') ?>">Give</a>
      </div>
      <div>
        <h4>Engage</h4>
        <a href="<?= url('portal') ?>">Learning Portal</a>
        <a href="<?= url('portal/register') ?>">Become a disciple</a>
        <a href="<?= url('contact') ?>">Contact us</a>
        <a href="<?= url('admin/login') ?>">Admin</a>
      </div>
      <div>
        <h4>Reach us</h4>
        <a href="mailto:<?= e($s['contact_email']) ?>"><?= e($s['contact_email']) ?></a>
        <a href="tel:<?= e($s['contact_phone']) ?>"><?= e($s['contact_phone']) ?></a>
        <p style="font-size:.9rem; margin-top:.4rem"><?= e($s['contact_address']) ?></p>
      </div>
    </div>
    <div class="footer-base">
      <span>&copy; <?= date('Y') ?> <?= e($s['site_name']) ?>. Founded by <?= e($s['founder']) ?>.</span>
      <span>Disciples making disciples.</span>
    </div>
  </div>
</footer>

<script src="<?= asset('js/app.js') ?>"></script>
<?php if ($editing): ?>
<script>window.MDLF = {
  base: <?= json_encode(base_url()) ?>,
  csrf: <?= json_encode(csrf_token()) ?>,
  pageId: <?= (int)($GLOBALS['APP']['page_id'] ?? 0) ?>,
  types: <?= json_encode(array_map(fn($d) => ['label' => $d['label'], 'icon' => $d['icon']], block_catalog())) ?>
};</script>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script src="<?= asset('js/media.js') ?>?v=1"></script>
<script src="<?= asset('js/builder.js') ?>?v=1"></script>
<?php endif; ?>
</body>
</html>
