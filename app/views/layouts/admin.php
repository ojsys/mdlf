<?php
/** @var string $content */
$s = settings();
$u = current_user();
$p = current_path();
$active = fn($needle) => strpos($p, $needle) === 0 ? 'active' : '';
$unread = (int) DB::value("SELECT COUNT(*) FROM messages WHERE is_read=0");
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Admin') ?> — <?= e($s['site_short']) ?> CMS</title>
<?php if (!empty($s['favicon'])): ?>
  <link rel="icon" href="<?= upload_url($s['favicon']) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,560;1,9..144,400&family=Karla:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('css/styles.css') ?>?v=3">
<link rel="stylesheet" href="<?= asset('css/media.css') ?>?v=1">
</head>
<body>
<?php require __DIR__ . '/_flash.php'; ?>
<div class="shell">
  <aside class="side admin-side">
    <a class="brand" href="<?= url('admin') ?>" style="color:#fff">
      <?php if (!empty($s['logo'])): ?>
        <img src="<?= upload_url($s['logo']) ?>" alt="Logo" style="height:40px; margin-right:.7rem">
      <?php else: ?>
        <?php require __DIR__ . '/_mark.php'; ?>
      <?php endif; ?>
      <span class="brand-text"><b style="color:#fff"><?= e($s['site_short']) ?></b><span style="color:rgba(255,255,255,.5)">Content Studio</span></span>
    </a>
    <nav>
      <a class="<?= $p==='/admin'?'active':'' ?>" href="<?= url('admin') ?>">▦ Dashboard</a>
      <a class="<?= $active('/admin/modules')?'active':'' ?>" href="<?= url('admin/modules') ?>">▤ Modules &amp; Lessons</a>
      <a class="<?= $active('/admin/posts')?'active':'' ?>" href="<?= url('admin/posts') ?>">◈ News &amp; Stories</a>
      <a class="<?= $active('/admin/pages')?'active':'' ?>" href="<?= url('admin/pages') ?>">📄 Pages</a>
      <a class="<?= $active('/admin/media')?'active':'' ?>" href="<?= url('admin/media') ?>">🖼 Media</a>
      <a class="<?= $active('/admin/objectives')?'active':'' ?>" href="<?= url('admin/objectives') ?>">🎯 Objectives</a>
      <a class="<?= $active('/admin/members')?'active':'' ?>" href="<?= url('admin/members') ?>">◇ Members</a>
      <a class="<?= $active('/admin/messages')?'active':'' ?>" href="<?= url('admin/messages') ?>">✉ Messages<?php if($unread): ?> <span class="badge unread" style="margin-left:auto"><?= $unread ?></span><?php endif; ?></a>
      <a class="<?= $active('/admin/settings')?'active':'' ?>" href="<?= url('admin/settings') ?>">⚙ Settings</a>
    </nav>
    <div class="side-foot">
      <a href="<?= url('/') ?>" style="display:block; color:rgba(255,255,255,.65); text-decoration:none; font-size:.88rem">↗ View website</a>
      <a href="<?= url('admin/logout') ?>" style="display:block; color:rgba(255,255,255,.65); text-decoration:none; margin-top:.5rem; font-size:.88rem">Sign out →</a>
    </div>
  </aside>
  <div class="main">
    <div class="topbar">
      <h1><?= e($heading ?? 'Dashboard') ?></h1>
      <span class="who"><?= e($u['name']) ?> · Admin</span>
    </div>
    <div class="content" style="max-width:1100px"><?= $content ?></div>
  </div>
</div>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
  // Initialize all Quill editors
  document.addEventListener('DOMContentLoaded', function() {
    const quillContainers = document.querySelectorAll('.quill-editor');
    quillContainers.forEach(function(container) {
      const inputId = container.dataset.inputId;
      const quill = new Quill(container, {
        theme: 'snow',
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link', 'image'],
            ['clean']
          ]
        }
      });
      
      // Set initial content
      const input = document.getElementById(inputId);
      if (input && input.value) {
        quill.root.innerHTML = input.value;
      }
      
      // Update hidden input on text change
      quill.on('text-change', function() {
        input.value = quill.root.innerHTML;
      });
    });
  });
</script>
<script src="<?= asset('js/app.js') ?>"></script>
<script>window.MDLF = { base: <?= json_encode(base_url()) ?>, csrf: <?= json_encode(csrf_token()) ?> };</script>
<script src="<?= asset('js/media.js') ?>?v=1"></script>
</body>
</html>
