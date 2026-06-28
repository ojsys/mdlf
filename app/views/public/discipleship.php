<?php $title = 'Discipleship'; ?>
<section class="hero" style="background:radial-gradient(120% 120% at 80% -10%, #16284A 0%, var(--ink) 55%, var(--ink-2) 100%)">
  <div class="wrap" style="padding-block:4.5rem 4rem; max-width:780px">
    <span class="eyebrow on-dark">Discipleship Learning Portal</span>
    <h1 style="font-size:clamp(2.2rem,4.6vw,3.3rem); margin:.8rem 0 1rem; color:var(--paper)">A path to grow — and to multiply.</h1>
    <p class="lead" style="color:rgba(247,248,251,.82)">Work through a structured discipleship curriculum at your own pace. Track your progress, gather resources, and become someone who can disciple others.</p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= url('portal') ?>">Go to my journey</a>
      <a class="btn btn-ghost on-dark" href="<?= url('portal/register') ?>">Create an account</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">The curriculum</span>
      <h2><?= count($modules) ?> modules, built to reproduce</h2>
    </div>
    <div class="card-grid">
      <?php foreach ($modules as $m): ?>
        <a class="card" href="<?= url('portal/module/' . $m['slug']) ?>">
          <div class="thumb module-cover"><span class="scr"><?= e($m['scripture']) ?></span></div>
          <div class="card-body">
            <span class="kicker">Module · <?= (int)$m['lesson_count'] ?> lesson<?= $m['lesson_count']==1?'':'s' ?></span>
            <h3><?= e($m['title']) ?></h3>
            <p><?= e($m['summary']) ?></p>
            <span class="meta">Open module →</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
