<?php /* Block: about_body — prose + objectives list + founder card. */
$objectives = is_array($b['objectives'] ?? null) ? $b['objectives'] : []; ?>
<section class="section">
  <div class="wrap" style="max-width:760px">
    <div class="prose" style="font-size:1.12rem; line-height:1.85">
      <?= $b['prose'] ?>
    </div>

    <div class="mt-4">
      <span class="eyebrow"><?= e($b['obj_eyebrow']) ?></span>
      <div class="stack-gap mt-2">
        <?php foreach ($objectives as $i => $o): ?>
          <div class="flex" style="align-items:flex-start; gap:1rem">
            <div class="num serif" style="color:var(--gold-deep); font-size:1.5rem; line-height:1"><?= sprintf('%02d', $i + 1) ?></div>
            <p class="muted" style="margin:0; padding-top:.2rem"><?= e($o) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="give-card lead mt-4">
      <span class="eyebrow on-dark"><?= e($b['founder_eyebrow']) ?></span>
      <h3 style="margin:.4rem 0 .6rem"><?= e($b['founder_name']) ?></h3>
      <p class="muted" style="color:rgba(247,248,251,.78)"><?= e($b['founder_desc']) ?></p>
    </div>
  </div>
</section>
