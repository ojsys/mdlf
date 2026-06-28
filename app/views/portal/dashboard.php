<?php $heading = 'My Journey'; $title = 'My Journey';
$first = explode(' ', current_user()['name'])[0]; ?>

<div class="progress-overview">
  <div class="ring" data-p="<?= (int)$overall ?>" style="--p:<?= (int)$overall ?>">
    <span><?= (int)$overall ?>%</span>
  </div>
  <div>
    <h2 class="serif" style="color:var(--paper); font-size:1.5rem">Keep going, <?= e($first) ?>.</h2>
    <p style="color:rgba(247,248,251,.7); margin:.3rem 0 0">
      You’ve completed <strong style="color:var(--gold-soft)"><?= (int)$totalDone ?></strong> of <?= (int)$totalLessons ?> lessons across <?= count($modules) ?> modules.
    </p>
  </div>
</div>

<?php if ($continue): ?>
  <div class="continue-card">
    <div class="grow">
      <span class="eyebrow">Continue where you left off</span>
      <h3 style="font-size:1.2rem; margin:.3rem 0 .2rem"><?= e($continue['title']) ?></h3>
      <p class="muted" style="margin:0; font-size:.92rem"><?= e($continue['module']['title']) ?></p>
    </div>
    <a class="btn btn-primary" href="<?= url('portal/lesson/' . $continue['slug']) ?>">Resume →</a>
  </div>
<?php endif; ?>

<span class="eyebrow">All modules</span>
<div class="mod-list mt-2">
  <?php foreach ($modules as $i => $m): $p = $m['progress']; ?>
    <a class="mod-row" href="<?= url('portal/module/' . $m['slug']) ?>">
      <div class="mod-index"><?= sprintf('%02d', $i+1) ?></div>
      <div>
        <h3 style="font-size:1.18rem; margin-bottom:.2rem"><?= e($m['title']) ?></h3>
        <p class="muted" style="margin:0 0 .6rem; font-size:.92rem"><?= e($m['summary']) ?></p>
        <div class="bar"><i style="width:<?= (int)$p['pct'] ?>%"></i></div>
      </div>
      <div class="pct">
        <?php if ($p['pct'] === 100): ?>
          <span class="badge published">Done</span>
        <?php else: ?>
          <?= (int)$p['done'] ?>/<?= (int)$p['total'] ?>
        <?php endif; ?>
      </div>
    </a>
  <?php endforeach; ?>
</div>
