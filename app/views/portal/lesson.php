<?php $heading = $lesson['title']; $title = $lesson['title'];
$typeLabel = ['video'=>'Video','pdf'=>'PDF','link'=>'Link','scripture'=>'Scripture','audio'=>'Audio']; ?>

<div class="reader">
  <div class="crumbs">
    <a href="<?= url('portal') ?>">My Journey</a> ·
    <a href="<?= url('portal/module/' . $module['slug']) ?>"><?= e($module['title']) ?></a>
  </div>

  <h1><?= e($lesson['title']) ?></h1>
  <p class="muted" style="margin-bottom:1.5rem"><?= (int)$lesson['duration_min'] ?> min · <?= e($module['scripture']) ?>
    <?php if ($lesson['done']): ?><span class="badge published" style="margin-left:.5rem">Completed</span><?php endif; ?>
  </p>

  <div class="prose">
    <?php foreach (preg_split('/\n\s*\n/', trim($lesson['content'])) as $para): ?>
      <p><?= nl2br(e($para)) ?></p>
    <?php endforeach; ?>
  </div>

  <?php if ($resources): ?>
    <div class="res-box">
      <h3>Resources for this lesson</h3>
      <?php foreach ($resources as $r): ?>
        <a class="res-item" href="<?= e($r['url'] ?: '#') ?>" <?= $r['url'] && $r['url']!=='#' ? 'target="_blank" rel="noopener"' : '' ?>>
          <span class="res-tag"><?= e($typeLabel[$r['type']] ?? 'Link') ?></span>
          <span style="flex:1"><?= e($r['label']) ?></span>
          <span style="color:var(--gold-deep)">↗</span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= url('portal/lesson/' . $lesson['slug'] . '/complete') ?>" style="margin-top:2rem">
    <?= csrf_field() ?>
    <div class="flex wrap-flex">
      <?php if (!$lesson['done']): ?>
        <button class="btn btn-primary" type="submit">✓ Mark as complete</button>
      <?php endif; ?>
      <?php if ($next): ?>
        <button class="btn <?= $lesson['done'] ? 'btn-primary' : 'btn-ghost' ?>" type="submit" name="next" value="1">
          <?= $lesson['done'] ? 'Next lesson →' : 'Complete &amp; continue →' ?>
        </button>
      <?php endif; ?>
    </div>
  </form>

  <div class="lesson-nav">
    <?php if ($prev): ?>
      <a class="btn btn-ghost btn-sm" href="<?= url('portal/lesson/' . $prev['slug']) ?>">← <?= e($prev['title']) ?></a>
    <?php else: ?><span></span><?php endif; ?>
    <?php if ($next): ?>
      <a class="btn btn-ghost btn-sm" href="<?= url('portal/lesson/' . $next['slug']) ?>"><?= e($next['title']) ?> →</a>
    <?php else: ?>
      <a class="btn btn-ghost btn-sm" href="<?= url('portal/module/' . $module['slug']) ?>">Back to module</a>
    <?php endif; ?>
  </div>
</div>
