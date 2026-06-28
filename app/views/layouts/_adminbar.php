<?php
/* Front-end admin bar — shown on public pages when an admin is signed in. */
if (!is_admin()) return;
$app         = $GLOBALS['APP'];
$isBlockPage = !empty($app['block_page']);
$editing     = !empty($app['editing']);
$hereUrl     = url(ltrim(current_path(), '/'));
?>
<div class="mdlf-adminbar<?= $editing ? ' editing' : '' ?>">
  <span class="mdlf-adminbar-brand">✦ <?= e($app['site_name'] ?? 'Studio') ?></span>
  <span class="mdlf-adminbar-links">
    <?php if ($isBlockPage && !$editing): ?>
      <a class="mdlf-pill primary" href="<?= e($hereUrl) ?>?edit=1">✎ Edit this page</a>
    <?php elseif ($editing): ?>
      <span class="mdlf-pill" id="mdlf-savestate" data-state="idle">Ready</span>
      <a class="mdlf-pill" href="<?= e($hereUrl) ?>">✓ Done</a>
    <?php endif; ?>
    <a class="mdlf-pill" href="<?= url('admin/pages/new') ?>">+ New page</a>
    <a class="mdlf-pill" href="<?= url('admin/pages') ?>">Pages</a>
    <a class="mdlf-pill" href="<?= url('admin') ?>">Dashboard</a>
    <a class="mdlf-pill" href="<?= url('admin/logout') ?>">Sign out</a>
  </span>
</div>
