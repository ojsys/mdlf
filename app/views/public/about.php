<?php $s = settings(); $title = 'About'; ?>
<section class="hero" style="background:radial-gradient(120% 120% at 80% -10%, #16284A 0%, var(--ink) 55%, var(--ink-2) 100%)">
  <div class="wrap" style="padding-block:4.5rem 4rem; max-width:760px">
    <span class="eyebrow on-dark">About the foundation</span>
    <h1 style="font-size:clamp(2.2rem,4.6vw,3.3rem); margin:.8rem 0 1rem; color:var(--paper)">A vision to renew leaders and restore communities.</h1>
    <p class="lead" style="color:rgba(247,248,251,.82)">Founded by <?= e($s['founder']) ?>, the Mipo Dadang Leadership Foundation was launched on March 30, 2024.</p>
  </div>
</section>

<section class="section">
  <div class="wrap" style="max-width:760px">
    <div class="prose" style="font-size:1.12rem; line-height:1.85">
      <p class="muted"><strong style="color:var(--ink)">The Mipo Dadang Leadership Foundation (MDLF)</strong> exists to meet people at the point of their real need — sociological, economic and spiritual — and to walk with them toward wholeness and purpose.</p>
      <p class="muted">We believe a leader is not finished until they have raised another leader. That conviction shapes our discipleship: relationships that don’t merely add, but multiply, so that those we teach can in turn teach others also.</p>
    </div>

    <div class="mt-4">
      <span class="eyebrow">Our objectives</span>
      <div class="stack-gap mt-2">
        <?php foreach ([
          'Address people’s sociological and economic needs with sustainable approaches.',
          'Help people have a right perspective toward human beings.',
          'Provide informal renewal and mentorship to emerging leaders.',
          'Build the capacity of persons to handle and manage the conflicts and crises of life with appropriate measures.',
          'Provide intervention for vulnerable persons to cope with life situations beyond temporal physical needs.',
        ] as $i => $o): ?>
          <div class="flex" style="align-items:flex-start; gap:1rem">
            <div class="num serif" style="color:var(--gold-deep); font-size:1.5rem; line-height:1"><?= sprintf('%02d', $i+1) ?></div>
            <p class="muted" style="margin:0; padding-top:.2rem"><?= e($o) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="give-card lead mt-4">
      <span class="eyebrow on-dark">The founder</span>
      <h3 style="margin:.4rem 0 .6rem"><?= e($s['founder']) ?></h3>
      <p class="muted" style="color:rgba(247,248,251,.78)">A pastor and mentor with a heart for emerging leaders, Rev. Mipo Dadang founded MDLF to see people restored and released to restore others — through discipleship, mentorship, and practical compassion.</p>
    </div>
  </div>
</section>
