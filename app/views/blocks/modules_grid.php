<?php /* Block: modules_grid (dynamic — modules) */
$limit       = (int)($b['limit'] ?? 3);
$showLessons = ($b['show_lessons'] ?? 'no') === 'yes';
$showAll     = ($b['show_all'] ?? 'yes') === 'yes';
$modules     = DB::all("SELECT * FROM modules WHERE status='published' ORDER BY sort_order");
$total       = count($modules);
$show        = $limit > 0 ? array_slice($modules, 0, $limit) : $modules;
if ($showLessons) {
    foreach ($show as &$m) {
        $m['lesson_count'] = (int) DB::value("SELECT COUNT(*) FROM lessons WHERE module_id=? AND status='published'", [$m['id']]);
    }
    unset($m);
} ?>
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow"><?= e($b['eyebrow']) ?></span>
      <h2><?= e($b['heading']) ?></h2>
      <?php if (trim((string)($b['description'] ?? '')) !== ''): ?>
        <p class="muted"><?= e($b['description']) ?></p>
      <?php endif; ?>
    </div>
    <div class="card-grid">
      <?php foreach ($show as $m): ?>
        <a class="card" href="<?= url('portal/module/' . $m['slug']) ?>">
          <div class="thumb module-cover"><span class="scr"><?= e($m['scripture']) ?></span></div>
          <div class="card-body">
            <?php if ($showLessons): ?>
              <span class="kicker">Module · <?= (int)$m['lesson_count'] ?> lesson<?= $m['lesson_count'] == 1 ? '' : 's' ?></span>
            <?php else: ?>
              <span class="kicker">Module</span>
            <?php endif; ?>
            <h3><?= e($m['title']) ?></h3>
            <p><?= e($m['summary']) ?></p>
            <span class="meta"><?= $showLessons ? 'Open module →' : 'Begin module →' ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php if ($showAll): ?>
      <div class="center mt-4">
        <a class="btn btn-ghost" href="<?= url('discipleship') ?>">See all <?= $total ?> modules</a>
      </div>
    <?php endif; ?>
  </div>
</section>
