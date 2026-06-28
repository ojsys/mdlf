<?php
$s = settings();
?>
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow on-dark"><?= e($s['hero_eyebrow'] ?? 'Mipo Dadang Leadership Foundation') ?></span>
      <h1><?= $s['hero_heading'] ?? 'Raising leaders who <em>raise leaders.</em>' ?></h1>
      <p class="lead"><?= e($s['hero_lead'] ?? ($s['tagline'] . ' We meet real needs, renew how people see one another, and mentor emerging leaders to carry the weight of life with grace.')) ?></p>
      <div class="hero-cta">
        <a class="btn btn-primary" href="<?= url($s['hero_cta_link'] ?? 'portal') ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
          </svg>
          <?= e($s['hero_cta_text'] ?? 'Start the discipleship journey') ?>
        </a>
        <a class="btn btn-ghost on-dark" href="<?= url('give') ?>">Partner with us</a>
      </div>
      <p class="hero-verse">&ldquo;<?= e($s['verse']) ?>&rdquo;<cite><?= e($s['verse_ref']) ?></cite></p>
    </div>
    <div class="hero-art">
      <!-- Signature: the multiplication motif — one disciple reproducing many -->
      <svg viewBox="0 0 440 360" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="One disciple multiplying many">
        <defs>
          <linearGradient id="g" x1="0" x2="1"><stop offset="0" stop-color="#F0D89A"/><stop offset="1" stop-color="#D7A33A"/></linearGradient>
        </defs>
        <!-- links -->
        <g stroke="rgba(240,216,154,.35)" stroke-width="1.5" fill="none">
          <path d="M220 60 C 220 110, 120 110, 120 150"/>
          <path d="M220 60 C 220 110, 320 110, 320 150"/>
          <path d="M120 150 C 120 200, 70 200, 70 240"/>
          <path d="M120 150 C 120 200, 170 200, 170 240"/>
          <path d="M320 150 C 320 200, 270 200, 270 240"/>
          <path d="M320 150 C 320 200, 370 200, 370 240"/>
          <path d="M70 240 L 50 300 M70 240 L 90 300"/>
          <path d="M170 240 L 150 300 M170 240 L 190 300"/>
          <path d="M270 240 L 250 300 M270 240 L 290 300"/>
          <path d="M370 240 L 350 300 M370 240 L 390 300"/>
        </g>
        <!-- generation 4 (small) -->
        <g fill="rgba(240,216,154,.55)">
          <circle cx="50" cy="306" r="5"/><circle cx="90" cy="306" r="5"/>
          <circle cx="150" cy="306" r="5"/><circle cx="190" cy="306" r="5"/>
          <circle cx="250" cy="306" r="5"/><circle cx="290" cy="306" r="5"/>
          <circle cx="350" cy="306" r="5"/><circle cx="390" cy="306" r="5"/>
        </g>
        <!-- generation 3 -->
        <g fill="#F0D89A"><circle cx="70" cy="240" r="9"/><circle cx="170" cy="240" r="9"/><circle cx="270" cy="240" r="9"/><circle cx="370" cy="240" r="9"/></g>
        <!-- generation 2 -->
        <g fill="url(#g)"><circle cx="120" cy="150" r="13"/><circle cx="320" cy="150" r="13"/></g>
        <!-- the first disciple -->
        <circle cx="220" cy="60" r="22" fill="url(#g)"/>
        <circle cx="220" cy="60" r="22" fill="none" stroke="#FBF8F1" stroke-width="2"/>
        <path d="M220 70V52 M220 56c0-5 4-8 9-8 0 5-4 8-9 8Z M220 60c0-5-4-8-9-8 0 5 4 8 9 8Z" fill="#0E1B33"/>
      </svg>
      <span class="cap">2 Timothy 2:2 — disciples making disciples</span>
    </div>
  </div>
</section>

<!-- Mission -->
<section class="mission">
  <div class="wrap">
    <div>
      <span class="eyebrow"><?= e($s['mission_eyebrow'] ?? 'Our mission') ?></span>
      <h2 style="font-size:1.9rem; margin-top:.6rem"><?= e($s['mission_heading'] ?? 'Restoring people, raising leaders.') ?></h2>
    </div>
    <p><?= e($s['mission']) ?></p>
  </div>
</section>

<!-- Impact stats -->
<section class="section dark">
  <div class="wrap">
    <div class="section-head center" style="text-align:center">
      <span class="eyebrow on-dark"><?= e($s['impact_eyebrow'] ?? 'Since March 30, 2024') ?></span>
      <h2><?= e($s['impact_heading'] ?? 'A young foundation, already bearing fruit') ?></h2>
    </div>
    <div class="stats">
      <?php foreach ($stats as $st): ?>
        <div class="stat">
          <div class="figure"><span data-count="<?= e($st['value']) ?>"><?= e($st['value']) ?></span><?= e($st['suffix']) ?></div>
          <div class="label"><?= e($st['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Objectives -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow"><?= e($s['objectives_eyebrow'] ?? 'What we are about') ?></span>
      <h2><?= e($s['objectives_heading'] ?? 'Six commitments that shape everything we do') ?></h2>
    </div>
    <div class="obj-grid">
      <?php foreach ($objectives as $i => $o): ?>
        <div class="obj">
          <div class="num"><?= sprintf('%02d', $i + 1) ?></div>
          <h3><?= e($o['title']) ?></h3>
          <p><?= e($o['description']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- June training feature -->
<section class="section" style="background:var(--paper); border-block:1px solid var(--line)">
  <div class="wrap">
    <div class="feature">
      <div class="media"><img src="<?= asset('img/training-group.jpeg') ?>" alt="The 24 youth leaders trained this June"></div>
      <div>
        <span class="eyebrow"><?= e($s['training_eyebrow'] ?? 'This June · 20–21') ?></span>
        <h2><?= e($s['training_heading'] ?? '24 youth leaders, trained to reproduce') ?></h2>
        <p class="muted" style="font-size:1.08rem"><?= e($s['training_description'] ?? 'Our Reproducible Leaders’ Discipleship Training equipped 24 young leaders not only to grow, but to multiply — to pass on what they received so others can teach others also.') ?></p>
        <p class="muted">It continues the work of a year in which <strong>206 young people</strong> were discipled and mentored, and many came to faith in Christ.</p>
        <div class="flex wrap-flex mt-2">
          <a class="btn btn-primary" href="<?= url('story/reproducible-leaders-training-june') ?>"><?= e($s['training_button_text'] ?? 'Read the story') ?></a>
          <a class="btn btn-ghost" href="<?= url('our-work') ?>">More from our work</a>
        </div>
      </div>
    </div>
    <div class="gallery mt-4">
      <figure><img src="<?= asset('img/session-focus.jpeg') ?>" alt="Listening in a training session"><figcaption>Attentive in session</figcaption></figure>
      <figure><img src="<?= asset('img/hall-wide.jpeg') ?>" alt="The training hall"><figcaption>Gathered together</figcaption></figure>
      <figure><img src="<?= asset('img/fellowship-meal.jpeg') ?>" alt="Sharing a meal"><figcaption>Fellowship &amp; meals</figcaption></figure>
      <figure><img src="<?= asset('img/training-group.jpeg') ?>" alt="Group photo of participants"><figcaption>The 24 leaders</figcaption></figure>
    </div>
  </div>
</section>

<!-- Modules preview -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow"><?= e($s['modules_eyebrow'] ?? 'Discipleship Learning Portal') ?></span>
      <h2><?= e($s['modules_heading'] ?? 'Learn, grow and multiply — module by module') ?></h2>
      <p class="muted"><?= e($s['modules_description'] ?? 'A structured discipleship curriculum you can work through at your own pace, with lessons and resources for each stage of the journey.') ?></p>
    </div>
    <div class="card-grid">
      <?php foreach (array_slice($modules, 0, 3) as $m): ?>
        <a class="card" href="<?= url('portal/module/' . $m['slug']) ?>">
          <div class="thumb module-cover"><span class="scr"><?= e($m['scripture']) ?></span></div>
          <div class="card-body">
            <span class="kicker">Module</span>
            <h3><?= e($m['title']) ?></h3>
            <p><?= e($m['summary']) ?></p>
            <span class="meta">Begin module →</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="center mt-4">
      <a class="btn btn-ghost" href="<?= url('discipleship') ?>">See all <?= count($modules) ?> modules</a>
    </div>
  </div>
</section>

<!-- Give CTA -->
<section class="section dark">
  <div class="wrap" style="display:grid; grid-template-columns:1.3fr 1fr; gap:2.5rem; align-items:center">
    <div>
      <span class="eyebrow on-dark"><?= e($s['give_cta_eyebrow'] ?? 'Help us go further') ?></span>
      <h2><?= e($s['give_cta_heading'] ?? 'The work is growing. The needs are real.') ?></h2>
      <p class="muted" style="font-size:1.05rem"><?= e($s['give_cta_description'] ?? 'Our greatest challenges are simple: we have few donors, and no mobility for the discipleship outreaches that take this work to where people are. Your partnership removes those barriers.') ?></p>
    </div>
    <div style="text-align:right">
      <a class="btn btn-primary" href="<?= url('give') ?>"><?= e($s['give_cta_button_text'] ?? 'Become a partner') ?></a>
    </div>
  </div>
</section>
