<?php $heading = $module['title']; $title = $module['title']; ?>

<a href="<?= url('portal') ?>" style="color:var(--gold-deep); text-decoration:none; font-weight:600; font-size:.9rem">← My Journey</a>

<div class="mt-2" style="display:grid; grid-template-columns:1fr auto; gap:1.5rem; align-items:center">
  <div>
    <span class="eyebrow">Module · <?= e($module['scripture']) ?></span>
    <h2 style="margin:.4rem 0 .5rem"><?= e($module['title']) ?></h2>
    <p class="muted" style="max-width:60ch"><?= e($module['description'] ?: $module['summary']) ?></p>
  </div>
  <div class="ring" data-p="<?= (int)$progress['pct'] ?>" style="--p:<?= (int)$progress['pct'] ?>; background:conic-gradient(var(--gold) calc(var(--p)*1%), var(--line) 0)">
    <span style="background:var(--parchment); color:var(--gold-deep)"><?= (int)$progress['pct'] ?>%</span>
  </div>
</div>

<div class="lesson-list mt-4">
  <?php foreach ($lessons as $i => $l): ?>
    <a class="lesson-item" href="<?= url('portal/lesson/' . $l['slug']) ?>">
      <div class="tick <?= $l['done'] ? 'done' : '' ?>"><?= $l['done'] ? '✓' : '' ?></div>
      <div class="t">
        <b><?= sprintf('%02d', $i+1) ?>. <?= e($l['title']) ?></b>
        <span><?= e($l['summary']) ?> · <?= (int)$l['duration_min'] ?> min</span>
      </div>
      <span style="color:var(--gold-deep); font-weight:700">→</span>
    </a>
  <?php endforeach; ?>
  <?php if (!$lessons): ?>
    <p class="muted">Lessons for this module are coming soon.</p>
  <?php endif; ?>
</div>
